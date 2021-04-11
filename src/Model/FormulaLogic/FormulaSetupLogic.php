<?php
declare(strict_types=1);

namespace App\Model\FormulaLogic;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use App\Model\Entity\FormulaGame;
use App\Model\Entity\FoDamage;
use App\Model\Entity\FoGame;
use App\Model\Entity\User;

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
        $this->FoLogs = $this->getTableLocator()->get('FoLogs');
        $this->FoTracks = $this->getTableLocator()->get('FoTracks');
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
        $this->addCars($formulaGame, $user);
    }
    
    public function addCars(FormulaGame $formulaGame, User $user) {
        $carCount = $formulaGame->fo_game->cars_per_player;
        $wearPoints = $formulaGame->fo_game->wear_points;
        $foCars = [];
        while ($carCount-- > 0) {
            $foDamages = [];
            $foDamages[] = new FoDamage([
                'wear_points' => $wearPoints - 5 * 3,
                'fo_e_damage_type_id' => 1, //tires get the remaining damage after 3 is assigned to everything else
            ]);
            for ($i = 2; $i <= 6; $i++) {
                $foDamages[] = new FoDamage([
                    'wear_points' => 3,
                    'fo_e_damage_type_id' => $i,
                ]);
            }
            $foCars[] = $this->FoCars->createUserCar($formulaGame->id, $user->id, $foDamages, true);
        }
        return $foCars;
    }

    public function getSetupUpdateJson($formulaGame, $user) {
        $formulaGame = $this->FormulaGames->get($formulaGame->id, ['contain' => [
            'FoGames', 'FoGames.FoTracks',
            'Users', 'FoCars', 'FoCars.FoDamages', 'FoGames.FoTracks',
        ]]);
        $foUsers = collection($formulaGame->users);
        $foCars = collection($formulaGame->fo_cars)->groupBy('user_id')->toArray();
        $foUsers->each(function(User $_user) use ($foCars, $user) {
            $_user->fo_cars = $foCars[$_user->id];
            $_user->editable = ($user->id === $_user->id);
            $_user->unset('_joinData');
        });
        $formulaGame->unset('fo_cars');
        $formulaGame->editable = $user->id === $formulaGame->creator_id;
        return $formulaGame;
    }
    
    public function editSetup(FormulaGame $formulaGame, array $data) {
        $this->FormulaGames->patchEntity($formulaGame, $data);
        $this->FormulaGames->patchEntity($formulaGame->fo_game, $data);
        $formulaGame->setDirty('fo_game');
        $this->FormulaGames->save($formulaGame);
    }
}
