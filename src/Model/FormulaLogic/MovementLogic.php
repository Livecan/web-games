<?php
declare(strict_types=1);

namespace App\Model\FormulaLogic;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use App\Model\Entity\FoMoveOption;
use App\Model\Entity\FoCar;
use App\Model\Entity\FoPosition2Position;
use App\Model\Entity\FoDamage;
use Cake\Database\Expression\QueryExpression;

/**
 * Description of MovementLogic
 *
 * @author roman
 */
class MovementLogic {
    use LocatorAwareTrait;
    
    public function __construct() {
        $this->FoPositions = $this->getTableLocator()->get('FoPositions');
        $this->FoCars = $this->getTableLocator()->get('FoCars');
        $this->FoDamages = $this->getTableLocator()->get('FoDamages');
        $this->FoPosition2Positions = $this->getTableLocator()->get('FoPosition2Positions');
        $this->FoDebris = $this->getTableLocator()->get('FoDebris');
        $this->FoMoveOptions = $this->getTableLocator()->get('FoMoveOptions');
        $this->FoCurves = $this->getTableLocator()->get('FoCurves');
    }
    
    public function getAvailableMoves(FoCar $foCar, int $movesLeft) : array {
        $moveOptions = collection([FoMoveOption::getFirstMoveOption(
                $foCar, $movesLeft, FoDamage::getZeroDamages())]);
        if ($movesLeft == 0) {
            return $moveOptions->toList();
        }
        
        $savedMoveOptions = $foCar->formula_game->getSavedMoveOptions();
        if (count($savedMoveOptions) > 0) {
            return $savedMoveOptions;
        }

        while (($currentMoveOption = $moveOptions->first())->np_moves_left > 0) {

            //take out the first FoMoveOption from the collection and remove it from it
            $currentMoveOption = $moveOptions->first();
            $moveOptions = $moveOptions->skip(1);
            
            $overtakingLeft = $this->canOvertake($foCar->game_id,
                    $currentMoveOption->fo_position_id) ? 3 : $currentMoveOption->np_overtaking;
            $position2Positions = $this->FoPositions->
                    get($currentMoveOption->fo_position_id)->
                    getNextAvailablePositions($foCar->game_id,
                            $currentMoveOption->np_allowed_left,
                            $currentMoveOption->np_allowed_right,
                            $overtakingLeft > 0);
            
            foreach ($position2Positions as $position2Position) {
                $moveOptions = FoMoveOption::addUniqueMoveOption($moveOptions,
                        $this->getNextPositionMoveOption($currentMoveOption,
                                $position2Position,
                                $overtakingLeft));
            }
            
            $moveOptions = FoMoveOption::addUniqueMoveOption($moveOptions, $this->getBrakingOption($currentMoveOption));
            
            $noMovesLeft = $moveOptions->count() > 0 && $moveOptions->first()->np_moves_left == 0;
            
            if ($noMovesLeft) {
                foreach ($moveOptions as $moveOption) {
                    if (!$moveOption->np_slipstream_checked && $moveOption->canSlipstream()) {
                        $moveOptions = $moveOptions->appendItem(debug($moveOption->getSlipstreamOption()));
                    }
                        
                }
            }
            
            $moveOptions = $moveOptions->sortBy('np_moves_left', SORT_DESC, SORT_NUMERIC);
        }
        foreach ($moveOptions as $moveOption) {
            $moveOption->adjustBrakeDamage();
            //if a car is carried into a curve when drafting, loses a brake point
            if ($moveOption->np_drafted_in_curve) {
                $moveOption->getDamageByType(FoDamage::TYPE_BRAKES)->wear_points++;
            }
        }
        
        if (!$moveOptions->isEmpty()) {
            $positionToCurve = $this->FoPositions->
                    find('list', ['keyField' => 'id', 'valueField' => 'fo_curve_id'])->
                    where(function(QueryExpression $exp, Query $q) use ($moveOptions) {
                        return $exp->in('id',
                                $moveOptions->extract('fo_position_id')->toList());
                    })->
                    toArray();
            //all the move options that are out of a turn need to have curve info nullified
            foreach ($moveOptions as $moveOption) {
                if ($positionToCurve[$moveOption->fo_position_id] === null) {
                    $moveOption->fo_curve_id = null;
                    $moveOption->stops = null;
                }
            }
        }
                
        $moveOptions = $moveOptions->filter(function(FoMoveOption $moveOption) use ($foCar) {
            return $foCar->isDamageOk(collection($moveOption->fo_damages),
                    collection([FoDamage::TYPE_TIRES, FoDamage::TYPE_BRAKES]));
        });
        
        $moveOptions = FoMoveOption::makeUnique($moveOptions);
        foreach ($moveOptions as $moveOption) {
            $moveOption->stops++;
        };
        return $this->FoMoveOptions->
                saveMany($moveOptions, ['associated' => ['FoDamages']])->
                toList();
    }
    
    private function canOvertake(int $gameId, int $positionFromId): bool {
        $foPosition2PositionStraight = $this->FoPosition2Positions->find('all')->
                contain(['FoPositionTo.FoCars' => function(Query $q) use ($gameId) {
                    return $q->select('FoCars.fo_position_id')
                            ->where(['FoCars.game_id' => $gameId]);
                }])->
                where(['fo_position_from_id' => $positionFromId,
                    'is_straight' => true])->
                first();
        if ($foPosition2PositionStraight != null &&
                $foPosition2PositionStraight->fo_position_to->fo_cars != null) {
            return true;
        }
        return false;
    }
    
    private function getNextPositionMoveOption(FoMoveOption $currentMoveOption,
            FoPosition2Position $nextPosition2Positions,
            int $overtaking = null): ?FoMoveOption {
        $nextMoveOption = clone $currentMoveOption;
        $nextMoveOption->fo_position_id = $nextPosition2Positions->fo_position_to_id;
        $nextMoveOption->np_moves_left = $currentMoveOption->np_moves_left - 1;
        $nextMoveOption->np_traverse = $currentMoveOption;
        $nextMoveOption->np_overtaking = 0;
        
        if ($overtaking == 3 || $overtaking == 2 && $nextPosition2Positions->is_straight) {
            $nextMoveOption->np_overtaking = $overtaking - 1;
        }
        
        if (!$nextPosition2Positions->fo_position_from->is_finish &&
                $nextPosition2Positions->fo_position_to->is_finish) {
            $nextMoveOption->is_next_lap = true;
        }
        
        if ($nextPosition2Positions->is_left || $nextPosition2Positions->is_curve) {
            $nextMoveOption->np_allowed_right = false;
        }
        if ($nextPosition2Positions->is_right || $nextPosition2Positions->is_curve) {
            $nextMoveOption->np_allowed_left = false;
        }
        $nextMoveOption->fo_damages = FoDamage::getDamagesCopy($currentMoveOption->fo_damages);
        $shocksDamage =collection(
                $this->FoDebris->findByFoPositionIdAndGameId($nextMoveOption->fo_position_id,
                        $currentMoveOption->fo_car->game_id))->
            count();
        $nextMoveOption->getDamageByType(FoDamage::TYPE_SHOCKS)->
                wear_points += $shocksDamage;
        $nextMoveOption = $this->processCurveHandlingDamage($nextMoveOption);
        $foCar = $this->FoCars->get($currentMoveOption->fo_car_id, ['contain' => ['FoDamages']]);
        
        if ($nextMoveOption->np_is_slipstreaming && $nextMoveOption->fo_curve_id != null) {
            $nextMoveOption->np_drafted_in_curve = true;
        }
        
        if ($nextMoveOption != null &&
                $foCar->isDamageOk(collection($nextMoveOption->fo_damages), collection([FoDamage::TYPE_TIRES]))) {
            return $nextMoveOption;
        } else {
            return null;
        }
    }
    
    private function getBrakingOption(FoMoveOption $moveOption): ?FoMoveOption {
        $moveOptionBrakeDamage = $moveOption->
                getDamageByType(FoDamage::TYPE_BRAKES)->wear_points + 1;
        $carBrakesWearPoints = $moveOption->fo_car->
                getDamageByType(FoDamage::TYPE_BRAKES)->wear_points;
        $carTiresWearPoints = $moveOption->fo_car->
                getDamageByType(FoDamage::TYPE_TIRES)->wear_points;
        $brakingOptionBrakesLeft = $carBrakesWearPoints - min($moveOptionBrakeDamage, 3);
        $brakingOptionTiresLeft = $carTiresWearPoints - max($moveOptionBrakeDamage - 3, 0);
        
        if ($brakingOptionBrakesLeft < 1 || $brakingOptionTiresLeft < 0 ||
               $moveOptionBrakeDamage >= 7) {    //can't add another brake damage
            return null;
        }
        
        $brakingOption = clone $moveOption;
        $brakingOption->np_moves_left = $moveOption->np_moves_left - 1;
        $brakingOption->np_traverse = $moveOption;
        $brakingOption->fo_damages = FoDamage::getDamagesCopy($moveOption->fo_damages);
        $brakingOption->getDamageByType(FoDamage::TYPE_BRAKES)->wear_points++;
        
        return $brakingOption;
    }
    
    private function processCurveHandlingDamage(FoMoveOption $nextMoveOption): ?FoMoveOption {
        
        $nextPosition = $this->FoPositions->get($nextMoveOption->fo_position_id,
                ['contain' => ['FoCurves']]);
        //entering a curve normally
        if ($nextMoveOption->fo_curve_id == null && $nextPosition->fo_curve_id != null) {
            $nextMoveOption->fo_curve_id = $nextPosition->fo_curve_id;
            $nextMoveOption->stops = 0;
            return $nextMoveOption;
        }
        
        //leaving a curve
        if ($nextMoveOption->fo_curve_id != null && $nextPosition->fo_curve_id == null &&
                !$nextMoveOption->np_overshooting) {
            $foCurve = $this->FoCurves->get($nextMoveOption->fo_curve_id);
            //leaving normally
            if ($nextMoveOption->stops >= $foCurve->stops) {
                $nextMoveOption->fo_curve_id = null;
                $nextMoveOption->stops = null;
                return $nextMoveOption;
            }
            //leaving skipping one stop
            if ($nextMoveOption->stops + 1 == $foCurve->stops) {
                $nextMoveOption->getDamageByType(FoDamage::TYPE_TIRES)->
                        wear_points++;
                return $nextMoveOption;
            }
            //otherwise overshooting by more than one stop - invalid MoveOption
            return null;
        }
        
        //when overshooting the second curve within one turn - invalid MoveOption
        if ($nextPosition->fo_curve_id == null && $nextMoveOption->np_overshooting) {
            return null;
        }
        
        //when overshooting into the second curve within one turn
        if ($nextMoveOption->fo_curve_id != null && $nextPosition->fo_curve_id != null &&
                $nextMoveOption->fo_curve_id != $nextPosition->fo_curve_id) {
            $nextMoveOption->fo_curve_id = $nextPosition->fo_curve_id;
            $nextMoveOption->stops = -1;
            $nextMoveOption->np_overshooting = true;
            $nextMoveOption->getDamageByType(FoDamage::TYPE_TIRES)->wear_points++;
            return $nextMoveOption;
        }
        
        return $nextMoveOption;
    }
}
