<?php
declare(strict_types=1);

namespace App\Model\FormulaLogic;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\I18n\Time;
use Cake\ORM\Entity;
use App\Model\Entity\FormulaGame;
use App\Model\Entity\FoDamage;
use App\Model\Entity\FoGame;
use App\Model\Entity\FoCar;
use App\Model\Entity\User;
use App\Model\Entity\FoLog;

/**
 * Description of FormulaSetupLogic
 *
 * @author roman
 */
class FormulaSetupLogic {
    use LocatorAwareTrait;
    
    public function __construct() {
        $this->FoCars = $this->getTableLocator()->get('FoCars');
        $this->FoDamages = $this->getTableLocator()->get('FoDamages');
        $this->FormulaGames = $this->getTableLocator()->get('FormulaGames');
        $this->FoGames = $this->getTableLocator()->get('FoGames');
        $this->FoLogs = $this->getTableLocator()->get('FoLogs');
        $this->FoTracks = $this->getTableLocator()->get('FoTracks');
        $this->FoPositions = $this->getTableLocator()->get('FoPositions');
        $this->Users = $this->getTableLocator()->get('Users');
    }
    
    public function createNewGame(User $user) {
        $foTrack = $this->FoTracks->find('all')->first();
                
        $formulaGame = new FormulaGame([
            'name' => $user->name . "'s " . $foTrack->name . " GP",
            'creator_id' => $user->id,
            'game_type_id' => 2,   //for Formula Game
            'fo_game' => 
                new FoGame([
                    'fo_track_id' => $foTrack->id, //for the first track - Monaco
                    'cars_per_player' => 2,
                    'wear_points' => 21,
                    'laps' => 2,
                ]),
            'users' => [],
        ]);
        $formulaGame = $this->FormulaGames->save($formulaGame, ['associated' => ['FoGames', 'Users']]);
        $this->addPlayer($formulaGame, $user);
        
        return $formulaGame;
    }
    
    public function addPlayer(FormulaGame $formulaGame, User $user) {
        $isUserAlreadyJoined = collection($formulaGame->users ?? [])->some(
            function($player) use ($user) { return $player->id == $user->id; });
        
        if ($isUserAlreadyJoined) {
            return;
        }
        
        $formulaGame->users[] = $user;
        $formulaGame->setDirty('users');
        $this->FormulaGames->save($formulaGame, ['associated' => ['Users']]);
        $this->addCars($formulaGame, $user->id);
    }
    
    public function addCars(FormulaGame $formulaGame, int $user_id, int $carCount = null) {
        if ($carCount == null) {
            $carCount = $formulaGame->fo_game->cars_per_player;
        }
        $foCars = [];
        while ($carCount-- > 0) {
            $foCars[] = $this->addCar($formulaGame, $user_id);
        }
        return $foCars;
    }
    
    public function addCar(FormulaGame $formulaGame, int $user_id) {
        $wearPoints = $formulaGame->fo_game->wear_points;
        $foDamages = [];
        $foDamages[] = new FoDamage([
            'wear_points' => $wearPoints - 5 * 3,
            'type' => FoDamage::TYPE_TIRES, //tires get the remaining damage after 3 is assigned to everything else
        ]);
        foreach ([ FoDamage::TYPE_GEARBOX,
                FoDamage::TYPE_BRAKES,
                FoDamage::TYPE_ENGINE,
                FoDamage::TYPE_CHASSIS,
                FoDamage::TYPE_SHOCKS, ] as $damageType) {
            $foDamages[] = new FoDamage([
                'wear_points' => 3,
                'type' => $damageType,
            ]);
        }
        return FoCar::createUserCar($formulaGame->id, $user_id, $foDamages);
    }

    public function getSetupUpdateJson(FormulaGame $formulaGame, $user, Time $modifiedDate = null) {
        $formulaGame = $this->FormulaGames->get($formulaGame->id, ['contain' => [
            'FoGames', 'FoGames.FoTracks',
            'Users', 'FoCars', 'FoCars.FoDamages', 'FoGames.FoTracks',
        ]]);
        foreach ($formulaGame->fo_game->toArray() as $property => $value) {
            $formulaGame->set($property, $value);
        }
        $formulaGame->unsetProperty('fo_game');
        if ($formulaGame->game_state_id == 2) {
            return new Entity([
                'has_updated' => true,
                'has_started' => true,]);
        }
        if ($modifiedDate >= $formulaGame->modified) {
            return new Entity(['has_updated' => false]);
        }
        $foUsers = collection($formulaGame->users);
        $foCars = collection($formulaGame->fo_cars)->groupBy('user_id')->toArray();
        $foUsers->each(function(User $_user) use ($formulaGame, $foCars, $user) {
            $_user->fo_cars = collection($foCars[$_user->id])->
                sortBy('id', SORT_ASC)->take($formulaGame->cars_per_player)->
                toList();
            $_user->editable = ($user->id === $_user->id);
            $_user->unset('_joinData');
        });
        $formulaGame->unset('fo_cars');
        $formulaGame->editable = $user->id === $formulaGame->creator_id;
        $formulaGame->has_updated = true;
        return $formulaGame;
    }
    
    public function editSetup(FormulaGame $formulaGame, array $data) {
        $this->FormulaGames->patchEntity($formulaGame, $data);
        $this->FoGames->patchEntity($formulaGame->fo_game, $data);
        $foTrack = $this->FoTracks->get($formulaGame->fo_game->fo_track_id);
        //TODO: check and fix loading of assiciations and rewrite the following
        $creator = $this->Users->get($formulaGame->creator_id);
        $formulaGame->name = $creator->name . "'s " . $foTrack->name . " GP";
        $formulaGame->setDirty('fo_game');
        
        $carsMissing = false;
        if (array_key_exists('cars_per_player', $data)) {
            $foUsersCarCount = collection($this->FoCars->find('all')->
                    where(['game_id' => $formulaGame->id]))->
                    groupBy('user_id')->
                    map(function($foUserCar) {
                        return count($foUserCar);
                    });
            $carsMissing = $foUsersCarCount->some(function($foUserCarCount) use ($formulaGame) {
                return $foUserCarCount < $formulaGame->fo_game->cars_per_player;
            });
        }
        if ($carsMissing) {
            foreach ($foUsersCarCount as $user_id => $foUserCarCount) {
                $this->addCars($formulaGame, $user_id, $formulaGame->fo_game->cars_per_player - $foUserCarCount);
            }
        }
        
        return $this->FormulaGames->save($formulaGame);
    }
    
    public function editDamage(FormulaGame $formulaGame, FoDamage $foDamage, int $wearPoints) {
        $foDamage->wear_points = $wearPoints;
        $foDamage = $this->FoDamages->save($foDamage);
        $formulaGame->modified = $foDamage->modified;
        $this->FormulaGames->save($formulaGame);
        return $foDamage;
    }
    
    public function joinGame(FormulaGame $formulaGame, User $user) {
        if ($formulaGame->max_players != null && count($formulaGame->users) >= $formulaGame->max_players) {
            return;
        }
        
        $isUserJoined = collection($formulaGame->users)->firstMatch(['user_id' => $user->id]) != null;
        if ($isUserJoined) {
            return;
        }
        
        $this->addCars($formulaGame, $user->id, $formulaGame->fo_game->cars_per_player);
        
        $formulaGame->users[] = $user;
        $formulaGame->setDirty('users');
        $this->FormulaGames->save($formulaGame, ['associated' => ['Users']]);
    }
    
    public function startGame(FormulaGame $formulaGame) {
        $foExcessCars = collection($formulaGame->fo_cars)->
                groupBy('user_id')->
                map(function($foUserCars) use ($formulaGame) {
                    if (count($foUserCars) <= $formulaGame->fo_game->cars_per_player) {
                        return [];
                    }
                    return collection($foUserCars)->
                            skip($formulaGame->fo_game->cars_per_player)->
                            toList();
                })->unfold();
        $this->FoCars->deleteMany($foExcessCars);
        $formulaGame->fo_cars = $this->FoCars->find('all')->
                where(['game_id' => $formulaGame->id])->
                toList();
        
        $formulaGame->fo_cars = collection($formulaGame->fo_cars)->shuffle();
        
        if ($formulaGame->fo_game->cars_per_player != 2) {
            $teamNumberIndexer = 0;
            foreach ($formulaGame->fo_cars as $foCar) {
                $teamNumberIndexer++;
                $foCar->team = (int)ceil($teamNumberIndexer / 2);
            }
        } else {
            $teamNumber = 0;
            $foCars = collection($formulaGame->fo_cars);
            foreach ($formulaGame->users as $user) {
                $teamNumber++;
                $foCars->filter(function(FoCar $foCar) use ($user) {
                    return $foCar->user_id == $user->id;
                })->
                each(function(FoCar $foCar) use ($teamNumber) {
                    $foCar->team = $teamNumber;
                });
            }
        }
        
        $startingPositions = $this->FoPositions->find('all')->
                select('id')->
                where(['fo_track_id' => $formulaGame->fo_game->fo_track_id])->
                whereNotNull('starting_position')->
                order(['starting_position' => 'ASC']);
        $formulaGame->fo_cars = $formulaGame->fo_cars->zip($startingPositions)->
                map(function($carPositionPair) {
                    $foCar = $carPositionPair[0];
                    $foCar->fo_position_id = $carPositionPair[1]->id;
                    $foCar->state = FoCar::STATE_RACING;
                    return $carPositionPair[0];
                })->toList();
        
        $formulaGame->game_state_id = 2;
        
        $formulaGame->setDirty('fo_cars');
        $formulaGame = $this->FormulaGames->save($formulaGame,
                        ['associated' => ['FoCars']]);

        $formulaGame->generateCarOrder();
        
        FoLog::logGameStart($formulaGame->fo_cars);

        return $formulaGame;
    }
    
    //TODO: before game starts, check damage points add up and do ready buttons functionality
}
