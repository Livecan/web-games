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
use App\Model\Entity\FoLog;
use App\Model\Entity\FoMoveOption;
use App\Model\Entity\FoDebri;

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
        $this->FoMoveOptions = $this->getTableLocator()->get('FoMoveOptions');
        $this->FoPosition2Positions = $this->getTableLocator()->get('FoPosition2Positions');
        $this->FoDebris = $this->getTableLocator()->get('FoDebris');
        $this->DiceLogic = new DiceLogic();
        $this->MovementLogic = new MovementLogic();
    }
    
    public function getBoard(FormulaGame $formulaGame, $user_id = null, Time $modifiedDateParam = null) {
        $foLogLastModified = $this->FoLogs->find('all')->
                contain(['FoCars'])->
                where(['FoCars.game_id' => $formulaGame->id])->
                order(['FoLogs.modified' => 'DESC'])->
                select(['FoLogs.modified'])->
                first();
        $modified = $foLogLastModified != null ? $foLogLastModified->modified : new Time();
        if ($modifiedDateParam != null && $modifiedDateParam >= $modified) {
            return new Entity(['has_updated' => false]);
        }
        
        $actions = $this->getActions($formulaGame, $user_id);
        
        $board = $this->FormulaGames->
                find('all')->
                contain([
                    'Users' => function(Query $q) {
                        return $q->select(['id', 'name']);
                    },
                    'FoCars' => function(Query $q) {
                        return $q->select(['id', 'game_id', 'user_id', 'fo_position_id', 'lap', 'gear', 'order', 'state'])->
                                order(['user_id' => 'ASC', 'FoCars.id' => 'ASC']);
                    },
                    'FoCars.FoDamages' => function(Query $q) {
                        return $q->whereNull(['fo_move_option_id'])->
                                select(['id', 'fo_car_id', 'wear_points', 'type'])->
                                order(['type' => 'ASC']);
                    },
                    'FoDebris' => function(Query $q) {
                        return $q->select(['game_id', 'fo_position_id']);
                    },
                    ])->
                select(['id', 'name', 'game_state_id'])->
                where(['id' => $formulaGame->id])->
                first();
        $board->fo_logs = $this->FoLogs->find('all')->
                contain(['FoCars'])->
                select($this->FoLogs)->
                where(['FoCars.game_id' => $formulaGame->id])->
                order(['FoLogs.created' => 'DESC']);
        if ($actions != null) {
            $board->actions = $actions;
        }
        $board->modified = $modified;
        $board->has_updated = true;
        return $board;
    }
    
    private function getActions(FormulaGame $formulaGame, $user_id) {
        
        $currentCar = $formulaGame->getNextCar();
        if ($currentCar == null) {
            $formulaGame->generateCarOrder();
            $currentCar = $formulaGame->getNextCar($formulaGame->id);
            $formulaGame->modified = new Time();
            $this->FormulaGames->save($formulaGame);
        }
        
        if ($currentCar["user_id"] != $user_id) {
            return;
        }
        
        $actions = new Entity();
        
        $lastCarTurn = $this->FoLogs->
                find('all')->
                contain(['FoCars'])->
                where(['fo_car_id' => $currentCar->id,
                    'type' => FoLog::TYPE_MOVE])->
                order(['FoLogs.id' => 'DESC'])->
                first();
        if ($lastCarTurn == null || $lastCarTurn['fo_position_id'] == null) {
            if ($lastCarTurn != null) {
                $movesLeft = $lastCarTurn['roll'];
            } else {
                $movesLeft = $currentCar->getStartMoveLength();
            }
            $actions->type = "choose_move";
            $actions->available_moves = $this->MovementLogic->getAvailableMoves($currentCar, $movesLeft);
            if (count($actions->available_moves) == 1) {
                $this->chooseMoveOption($formulaGame, $actions->available_moves[0]);
                return $this->getActions($formulaGame, $user_id);
            }
            if (count($actions->available_moves) == 0) {
                $currentCar = $currentCar->retire();
            }
        }
        if ($lastCarTurn != null && $lastCarTurn['fo_position_id'] != null) {
            if ($currentCar->gear == 0) {
                $this->chooseGear($formulaGame, 1);
                $formulaGame = $this->FormulaGame->get($formulaGame->id, ['contains' => ['FoCars']]);
                return $this->getActions($formulaGame, $user_id);
            }
            $actions->type = "choose_gear";
            $actions->current_gear = $currentCar->gear;
            $actions->available_gears = [];
            $downshiftAvailable = 4;
            if ($currentCar->getDamageByType(FoDamage::TYPE_ENGINE)->wear_points <= 1) {
                $downshiftAvailable = 3;
            }
            if ($currentCar->getDamageByType(FoDamage::TYPE_BRAKES)->wear_points <= 1) {
                $downshiftAvailable = 2;
            }
            if ($currentCar->getDamageByType(FoDamage::TYPE_GEARBOX)->wear_points <= 1) {
                $downshiftAvailable = 1;
            }
            for ($availableGear = max(1, $currentCar->gear - $downshiftAvailable);
                    $availableGear <= min(6, $currentCar->gear + 1);
                    $availableGear++) {
                $actions->available_gears[] = $availableGear;
            }
        }
        return $actions;
    }
    
    public function chooseMoveOptionById(FormulaGame $formulaGame, int $foMoveOptionId) {
        $foMoveOption = $this->FoMoveOptions->get($foMoveOptionId, ['contain' => ['FoDamages']]);
        $this->chooseMoveOption($formulaGame, $foMoveOption);
    }
    
    //FIX & //REFACTORING: tidy the function, rewrite methods to OO and do move, then collisions
    public function chooseMoveOption(FormulaGame $formulaGame, FoMoveOption $foMoveOption) {
        $foPositionId = $foMoveOption->fo_position_id;
        
        $foCar = $foMoveOption->fo_car;
        $foLogMove = $this->FoLogs->find('all')->
                contain(['FoCars'])->
                where(['fo_car_id' => $foCar->id, 'type' => FoLog::TYPE_MOVE])->
                order(['FoLogs.id' => 'DESC'])->
                first();
        $foLogMove->fo_position_id = $foPositionId;
        $foLogMove->save();
        
        $foCar->fo_position_id = $foPositionId;
        $foCar->fo_curve_id = $foMoveOption->fo_curve_id;
        $foCar->stops = $foMoveOption->stops;
        if ($foMoveOption->is_next_lap) {
            $foCar->lap++;
        }
        $foCar->order = null;
        
        $foCar->assignMovementDamages($foMoveOption->fo_damages);
        
        //check if car is finishing the race now and if so assign ranking and skip collisions
        if ($foCar->lap > $formulaGame->fo_game->laps) {
            $foCar->state = FoCar::STATE_FINISHED;
            $foCar->ranking = $formulaGame->getNextRanking();
        }
        
        $foCar->save();

        if ($foCar->state != FoCar::STATE_FINISHED) {
            $collidedCars = $this->getCollidedCars($formulaGame->id, $foPositionId);
            foreach ($collidedCars as $collidedCar) {
                $isCausedDamage = $collidedCar->assignDamage(
                        FoDamage::getOneDamage(FoDamage::TYPE_CHASSIS),
                        DiceLogic::BLACK_COLLISION_TOP);
                if ($isCausedDamage) {
                    $this->FoDebris->save(new FoDebri([
                        'game_id' => $formulaGame->id,
                        'fo_position_id' => $collidedCar->fo_position_id]));
                }
                $isCausedDamage = $foCar->assignDamage(
                        FoDamage::getOneDamage(FoDamage::TYPE_CHASSIS),
                        DiceLogic::BLACK_COLLISION_TOP);
                if ($isCausedDamage) {
                    $this->FoDebris->save(new FoDebri([
                        'game_id' => $formulaGame->id,
                        'fo_position_id' => $foCar->fo_position_id]));
                }
            }
        }
        
        $moveOptionsToDelete = $this->FoMoveOptions->find('all')->
                where(['fo_car_id' => $foCar->id])->
                toList();
        $this->FoMoveOptions->deleteMany($moveOptionsToDelete);
    }
    
    public function getCollidedCars(int $gameId, int $foPositionId) {
        return collection($this->FoCars->find('all')->
            contain(['FoPositions.FoPosition2PositionsFrom' => function(Query $q) use ($foPositionId) {
                return $q->where(['is_adjacent' => true])->
                        where(['fo_position_to_id' => $foPositionId]);
            }, 'FoPositions.FoPosition2PositionsTo' => function(Query $q) use ($foPositionId) {
                return $q->where(['is_adjacent' => true])->
                        where(['fo_position_from_id' => $foPositionId]);
            }, 'FoDamages'])->
            where(['game_id' => $gameId]))->
        filter(function(FoCar $foCar) {
            return $foCar->fo_position->fo_position2_positions_from != null ||
                    $foCar->fo_position->fo_position2_positions_to != null;
        })->toList();
    }
    
    public function chooseGear(FormulaGame $formulaGame, int $gear) {
        $currentCar = $formulaGame->getNextCar($formulaGame->id);
        if ($gear < max($currentCar->gear - 4, 1) || $gear > min($currentCar->gear + 1, 6)) {
            return;
        }
        
        $roll = $currentCar->shift($gear);
        
        if ($roll == 20 || $roll == 30) {
            $formulaGame->assignEngineDamages();
        }
    }
}
