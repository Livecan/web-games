<?php
declare(strict_types=1);

namespace App\Model\FormulaLogic;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\Entity;

/**
 * Description of FormulaLogic
 *
 * @author roman
 */
class FormulaLogic {
    use LocatorAwareTrait;
    
    public function __construct() {
        $this->FoPositions = $this->getTableLocator()->get('FoPositions');
        $this->FoCars = $this->getTableLocator()->get('FoCars');
        $this->FoDamages = $this->getTableLocator()->get('FoDamages');
        $this->Users = $this->getTableLocator()->get('Users');
        $this->FormulaGames = $this->getTableLocator()->get('FormulaGames');
        $this->FoLogs = $this->getTableLocator()->get('FoLogs');
        $this->MovementLogic = new MovementLogic();
    }
    
    public function start($formulaGame) {
        $formulaGame->fo_cars = collection($formulaGame->users)->map(function($user) use ($formulaGame) {
            $playerCarsLeft = $formulaGame->fo_game->cars_per_player;
            while ($playerCarsLeft-- > 0) {
                $damages = $this->FoDamages->newEntities([
                        ['fo_e_damage_type_id' => 1, 'wear_points' => 7],
                        ['fo_e_damage_type_id' => 2, 'wear_points' => 3],
                        ['fo_e_damage_type_id' => 3, 'wear_points' => 3],
                        ['fo_e_damage_type_id' => 4, 'wear_points' => 3],
                        ['fo_e_damage_type_id' => 5, 'wear_points' => 3],
                        ['fo_e_damage_type_id' => 6, 'wear_points' => 3],]);
                yield $this->FoCars->createUserCar($formulaGame->id, $user->id, $damages, false);
            }
        })->unfold()->shuffle();
        
        $startingPositions = $this->FoPositions->find('all')->
                select('id')->
                where(['fo_track_id' => $formulaGame->fo_game->fo_track_id])->
                whereNotNull('starting_position')->
                order(['starting_position' => 'ASC']);
        $formulaGame->fo_cars = $formulaGame->fo_cars->zip($startingPositions)->
                map(function($carPositionPair) {
                    $carPositionPair[0]->fo_position_id = $carPositionPair[1]->id;
                    return $carPositionPair[0];
                })->toList();
        
        $formulaGame->game_state_id = 2;
        
        $formulaGame->setDirty('fo_card',true);
        $formulaGame = $this->FormulaGames->save($formulaGame,
                        ['associated' => ['FoCars', 'FoCars.FoDamages']]);
        $formulaGame->fo_cars = $this->FoCars->generateCarOrder($formulaGame->id)->toList();
        
        $this->FoLogs->logGameStart($formulaGame->fo_cars);
        
        return $formulaGame;
    }
    
    public function getBoard($formulaGame, $user_id = null) {
        $board = $this->FormulaGames->
                find('all')->
                contain([
                    'Users' => function(Query $q) {
                        return $q->select(['id', 'name']);
                    },
                    'FoCars' => function(Query $q) {
                        return $q->select(['id', 'game_id', 'user_id', 'lap', 'gear'])->
                                order(['user_id' => 'ASC', 'FoCars.id' => 'ASC']);
                    },
                    'FoCars.FoDamages' => function(Query $q) {
                        return $q->whereNull(['fo_move_option_id'])->
                                select(['id', 'fo_car_id', 'wear_points', 'fo_e_damage_type_id'])->
                                order(['fo_e_damage_type_id' => 'ASC']);
                    },
                    'FoCars.FoPositions' => function(Query $q) {
                        return $q->select(['pos_x', 'pos_y', 'angle']);
                    }
                    ])->
                select(['id', 'name', 'game_state_id'])->
                where(['id' => $formulaGame->id])->
                first();
        $board->actions = $this->getActions($formulaGame, $user_id);   //TODO: remove, used for testing
        return $board;
    }
    
    public function getActions($formulaGame, $user_id) {
        $currentCar = $this->FoCars->
                find('all')->
                where(['game_id' => $formulaGame->id])->
                whereNotNull('order')->
                order(['order' => 'ASC'])->
                first();
        
        if ($currentCar["user_id"] != $user_id) {
            return;
        }
        
        $actions = new Entity();
        
        $lastCarTurn = $this->FoLogs->
                find('all')->
                contain(['FoCars'])->
                where(['game_id' => $formulaGame->id,
                    "fo_car_id" => $currentCar->id,
                    "type" => 'M'])->
                order(['FoLogs.modified' => 'DESC'])->
                first();
        
        if ($lastCarTurn == null || $lastCarTurn['fo_position_id'] == null) {
            if ($lastCarTurn != null) {
                $movesLeft = $lastCarTurn['roll'];
            } else {
                $movesLeft = $this->FoCars->getNextMoveLength($currentCar);
            }
            $actions->type = "available_moves";
            $actions->available_moves = $this->MovementLogic->getAvailableMoves($currentCar, $movesLeft);
        }
        return $actions;
    }
}
