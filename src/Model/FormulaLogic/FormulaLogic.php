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
        
        $currentCar = $this->FoCars->getNextCar($formulaGame->id);
        if ($currentCar == null) {
            $this->FoCars->generateCarOrder($formulaGame->id);
            $currentCar = $this->FoCars->getNextCar($formulaGame->id);
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
                where(['game_id' => $formulaGame->id,
                    "fo_car_id" => $currentCar->id,
                    "type" => FoLog::TYPE_MOVE])->
                order(['FoLogs.id' => 'DESC'])->
                first();
        if ($lastCarTurn == null || $lastCarTurn['fo_position_id'] == null) {
            if ($lastCarTurn != null) {
                $movesLeft = $lastCarTurn['roll'];
            } else {
                $movesLeft = $this->FoCars->getNextMoveLength($currentCar);
            }
            $actions->type = "choose_move";
            $actions->available_moves = $this->MovementLogic->getAvailableMoves($currentCar, $movesLeft);
            if (count($actions->available_moves) == 1) {
                $this->chooseMoveOption($formulaGame, $actions->available_moves[0]);
                return $this->getActions($formulaGame, $user_id);
            }
            if (count($actions->available_moves) == 0) {
                $this->FoCars->retireCar($currentCar);
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
            $foCarDamages = collection($currentCar->fo_damages);
            $downshiftAvailable = 4;
            if ($foCarDamages->firstMatch(['type' => FoDamage::TYPE_ENGINE])) {
                $downshiftAvailable = 3;
            }
            if ($foCarDamages->firstMatch(['type' => FoDamage::TYPE_BRAKES])) {
                $downshiftAvailable = 2;
            }
            if ($foCarDamages->firstMatch(['type' => FoDamage::TYPE_GEARBOX])) {
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
    
    public function chooseMoveOption(FormulaGame $formulaGame, FoMoveOption $foMoveOption) {
        $foCar = $this->FoCars->get($foMoveOption->fo_car_id, ['contain' => ['FoDamages']]);
        $foPositionId = $foMoveOption->fo_position_id;
        $foCar->fo_position_id = $foPositionId;
        $foCar->fo_curve_id = $foMoveOption->fo_curve_id;
        $foCar->stops = $foMoveOption->stops;
        if ($foMoveOption->is_next_lap) {
            $foCar->lap++;
        }
        $foCar->order = null;
        
        $damagesSuffered = [];
        
        foreach ($foMoveOption->fo_damages as $foDamage) {
            $damageSuffered;
            switch ($foDamage->type) {
                case (FoDamage::TYPE_TIRES):
                    $damageSuffered = $this->FoDamages->assignDamage($foCar->fo_damages, $foDamage);
                    break;
                case (FoDamage::TYPE_BRAKES):
                    $damageSuffered = $this->FoDamages->assignDamage($foCar->fo_damages, $foDamage);
                    break;
                case (FoDamage::TYPE_SHOCKS):
                    $damageSuffered = $this->FoDamages->assignDamage($foCar->fo_damages, $foDamage, 4);
                    break;
            }
            if ($damageSuffered > 0) {
                $damagesSuffered[] = new FoDamage([
                    'type' => $foDamage->type,
                    'wear_points' => $damageSuffered,
                ]);
            }
        }
        $foCarTireDamage = collection($foCar->fo_damages)->firstMatch(['type' => FoDamage::TYPE_TIRES]);
        if ($foCarTireDamage->wear_points == 0) {
            $foCar->gear = 0;
            $foCarTireDamage->wear_points = 1;
        }
        if (!$foCar->isOk()) {
            $foCar->state = FoCar::STATE_RETIRED;
        }
        debug($foCar);
        $collidedCars = $this->getCollidedCars($formulaGame->id, $foPositionId);
        foreach ($collidedCars as $collidedCar) {
            $isCausedDamage = $this->FoDamages->assignDamageSimple($collidedCar->fo_damages,
                    1, FoDamage::TYPE_CHASSIS, DiceLogic::BLACK_COLLISION_THRESHOLD, true);
            if ($isCausedDamage) {
                $this->FoDebris->save(new FoDebri([
                    'game_id' => $formulaGame->id,
                    'fo_position_id' => $collidedCar->fo_position_id]));
            }
            $damageSuffered = $this->FoDamages->assignDamageSimple(
                    $foCar->fo_damages, 1, FoDamage::TYPE_CHASSIS, DiceLogic::BLACK_COLLISION_THRESHOLD, false);
            if ($damageSuffered > 0) {
                $damagesSuffered[] = new FoDamage([
                    'type' => FoDamage::TYPE_CHASSIS,
                    'wear_points' => $damageSuffered,
                ]);
                $this->FoDebris->save(new FoDebri([
                    'game_id' => $formulaGame->id,
                    'fo_position_id' => $foCar->fo_position_id]));
            }
        }
        $foCar->setDirty('fo_damages', true);
        $this->FoCars->save($foCar, ['associated' => ['FoDamages']]);
        $foLog = $this->FoLogs->find('all')->
                contain(['FoCars'])->
                where(['fo_car_id' => $foCar->id, 'type' => FoLog::TYPE_MOVE])->
                order(['FoLogs.id' => 'DESC'])->
                first();
        $foLog->fo_position_id = $foPositionId;
        $foLog->fo_damages = $damagesSuffered;
        $foLog->setDirty('fo_damages', true);
        $this->FoLogs->save($foLog, ['associated' => ['FoDamages']]);
        
        $moveOptionsToDelete = $this->FoMoveOptions->find('all')->
                where(['fo_car_id' => $foCar->id])->
                toList();
        $this->FoMoveOptions->deleteMany($moveOptionsToDelete);
        
        //TODO: check if any car is retired
    }
    
    public function getCollidedCars(int $gameId, int $foPositionId) {
        return collection($this->FoCars->find('all')->
            contain(['FoPositions.FoPosition2PositionsFrom' => function(Query $q) use ($foPositionId) {
                return $q->where(['is_adjacent' => true])->
                        where(['OR' => ['fo_position_from_id' => $foPositionId,
                            'fo_position_to_id' => $foPositionId]]);
            }, 'FoPositions.FoPosition2PositionsTo' => function(Query $q) use ($foPositionId) {
                return $q->where(['is_adjacent' => true])->
                        where(['OR' => ['fo_position_from_id' => $foPositionId,
                            'fo_position_to_id' => $foPositionId]]);
            }, 'FoDamages'])->
            where(['game_id' => $gameId]))->
        filter(function(FoCar $collidedCar) {
            return $collidedCar->fo_position->fo_position2_positions_from != null ||
                    $collidedCar->fo_position->fo_position2_positions_to != null;
        })->toList();
    }
    
    public function chooseGear(FormulaGame $formulaGame, int $gear) {
        $currentCar = $this->FoCars->getNextCar($formulaGame->id);
        //TODO: include checking if the player chose an option that doesn't destroy him - the check is already in getActions(), so might not be necessary here
        if ($gear < max($currentCar->gear - 4, 1) || $gear > min($currentCar->gear + 1, 6)) {
            return;
        }
        $currentCar = $this->FoCars->get($currentCar->id, ['contain' => ['FoDamages']]);
        $gearDiff = $gear - $currentCar->gear;
        $currentCar->gear = $gear;
        $foLog = new FoLog([
            'fo_car_id' => $currentCar->id,
            'gear' => $gear,
            'roll' => $this->DiceLogic->getRoll($gear),
            'type' => FoLog::TYPE_MOVE,
        ]);
        $this->FoCars->save($currentCar);
        //damage for too much downshifting:
        $foLog->fo_damages = $this->FoDamages->processGearChangeDamage($currentCar, $gearDiff);
        $this->FoLogs->save($foLog, ['associated' => ['FoDamages']]);
    }
}
