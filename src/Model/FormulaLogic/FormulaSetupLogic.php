<?php
declare(strict_types=1);

namespace App\Model\FormulaLogic;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\I18n\Time;
use Cake\ORM\Entity;
use App\Model\Entity\FormulaGame;
use App\Model\Entity\FoDamage;
use App\Model\FormulaLogic\DiceLogic;
use App\Model\Entity\FoCar;
use App\Model\Entity\FoGame;
use App\Model\Entity\FoLog;
use App\Model\Entity\User;

/**
 * Description of FormulaSetupLogic
 *
 * @author roman
 */
class FormulaSetupLogic {
    use LocatorAwareTrait;
    
    public function __construct() {
        $this->FoPositions = $this->getTableLocator()->get('FoPositions');
        $this->FoCars = $this->getTableLocator()->get('FoCars');
        $this->FoDamages = $this->getTableLocator()->get('FoDamages');
        $this->Users = $this->getTableLocator()->get('Users');
        $this->FormulaGames = $this->getTableLocator()->get('FormulaGames');
        $this->FoLogs = $this->getTableLocator()->get('FoLogs');
        $this->FoMoveOptions = $this->getTableLocator()->get('FoMoveOptions');
        $this->FoPosition2Positions = $this->getTableLocator()->get('FoPosition2Positions');
        $this->FoMoveOptions = $this->getTableLocator()->get('FoMoveOptions');
    }
    
    public function createNewGame(User $user) {
        $formulaGame = new FormulaGame([
            'name' => $user->name . "'s game",
            'creator_id' => $user->id,
            'game_type_id' => 2,   //for Formula Game
            'fo_game' => 
                new FoGame([
                    'fo_track_id' => 1, //for the first track - Monaco
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
        $carCount = debug($formulaGame)->fo_game->cars_per_player;
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
            $foCars[] = debug($this->FoCars->createUserCar($formulaGame->id, $user->id, $foDamages, true));
        }
        return $foCars;
    }

}
