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
use Cake\Collection\CollectionInterface;

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
        if ($movesLeft == 0) {
            return [new FoMoveOption(['fo_car_id' => $foCar->id,
                    'fo_position_id' => $foCar->fo_position_id,
                    'is_next_lap' => false,
                    'fo_damages' => $this->getZeroDamages(),
               ])];
        }
        $savedMoveOptions = $this->FoMoveOptions->getSavedMoveOptions($foCar->game_id);
        if (count($savedMoveOptions) > 0) {
            return $savedMoveOptions;
        }

        $moveOptions = collection([$this->FoMoveOptions->getFirstMoveOption(
                $foCar->id, $foCar->fo_position_id, $movesLeft, $this->getZeroDamages())]);

        $currentMoveOption;
        while (($currentMoveOption = $moveOptions->first())->np_moves_left > 0) {

            //take out the first FoMoveOption from the collection and remove it from it
            $currentMoveOption = $moveOptions->first();
            $moveOptions = $moveOptions->skip(1);
            
            $overtakingLeft = $this->canOvertake($foCar->game_id,
                    $currentMoveOption->fo_position_id) ? 3 : $currentMoveOption->np_overtaking;
            $position2Positions = $this->getNextAvailablePositions(
                    $foCar->game_id,
                    $currentMoveOption,
                    $overtakingLeft > 0);

            $nextPositionMoveOptions = $position2Positions->
                    map(function($position2Positions) use ($currentMoveOption, $overtakingLeft) {
                        return $this->getNextPositionMoveOption($currentMoveOption, $position2Positions, $overtakingLeft);
                    });
            $nextPositionMoveOptions = $nextPositionMoveOptions->appendItem($this->getBrakingOption($currentMoveOption));
            
            $moveOptions = $this->addUniqueMoveOptions($moveOptions, $nextPositionMoveOptions);
            
            $moveOptions = $moveOptions->sortBy('np_moves_left', SORT_DESC, SORT_NUMERIC);
        }
        //TODO: after this is finished, do drafting if conditions for drafting are met
        $moveOptions = $this->adjustBrakeDamage($moveOptions);
        
        if (!$moveOptions->isEmpty()) {
            $moveOptionsPositionsIsCurve = $this->FoPositions->
                    find('list', ['keyField' => 'id', 'valueField' => 'fo_curve_id'])->
                    where(function(QueryExpression $exp, Query $q) use ($moveOptions) {
                        return $exp->in('id',
                                $moveOptions->extract('fo_position_id')->toList());
                    })->
                    toArray();
            //all the move options that are out of a turn need to have curve info nullified
            $moveOptions->each(function(FoMoveOption $moveOption) use ($moveOptionsPositionsIsCurve) {
                if (!$moveOptionsPositionsIsCurve[$moveOption->fo_position_id]) {
                    $moveOption->fo_curve_id = null;
                    $moveOption->stops = null;
                }
            });
        }
                
        $moveOptions = $moveOptions->filter(function(FoMoveOption $moveOption) use ($foCar) {
            return $foCar->isDamageOk(collection($moveOption->fo_damages),
                    collection([FoDamage::TYPE_TIRES, FoDamage::TYPE_BRAKES]));
        });
        
        $moveOptions = $this->makeUnique($moveOptions);
        $moveOptions->each(function(FoMoveOption $moveOption) {
            $moveOption->stops++;
        });
        return $this->FoMoveOptions->
                saveMany($moveOptions, ['associated' => ['FoDamages']])->
                toList();
    }
    
    private function getNextAvailablePositions(int $gameId, FoMoveOption $moveOption, bool $overtaking) : CollectionInterface {
        //following returns all the possible next moves, excluding fields where other cars are
        $query = $this->FoPosition2Positions->find('all')-> 
                contain([
                    'FoPositionFrom' => function(Query $q) {
                        return $q->select(['id', 'is_finish']);
                    },
                    'FoPositionTo' => function(Query $q) {
                        return $q->select(['id', 'is_finish']);
                    },
                    'FoPositionTo.FoCars' => function(Query $q) use ($gameId) {
                        return  $q->where(['game_id' => $gameId])->
                                select('fo_position_id');
                    },
               ])->
                select(['fo_position_to_id', 'is_left', 'is_straight', 'is_right', 'is_curve', 'is_pitlane_move'])->
                where(['fo_position_from_id' => $moveOption->fo_position_id,
                    'OR' => [['is_left' => true],
                        ['is_straight' => true],
                        ['is_right' => true],
                        ['is_curve' => true],
                   ]
               ]);

        if (!$moveOption->np_allowed_left && !$overtaking) {
            $query = $query->where(['is_left' => false]);
        }
        if (!$moveOption->np_allowed_right && !$overtaking) {
            $query = $query->where(['is_right' => false]);
        }

        return collection($query)->
            filter(function(FoPosition2Position $foPosition2Position) {
                return count($foPosition2Position->fo_position_to->fo_cars) == 0;
            })->
            buffered();
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
        $nextMoveOption = new FoMoveOption(['fo_car_id' => $currentMoveOption->fo_car_id,
            'fo_car' => $currentMoveOption->fo_car,
            'fo_position_id' => $nextPosition2Positions->fo_position_to_id,
            'fo_curve_id' => $currentMoveOption->fo_curve_id,
            'stops' => $currentMoveOption->stops,
            'is_next_lap' => $currentMoveOption->is_next_lap,
            'np_moves_left' => ($currentMoveOption->np_moves_left - 1),
            'np_allowed_left' => $currentMoveOption->np_allowed_left,
            'np_allowed_right' => $currentMoveOption->np_allowed_right,
            'np_overshooting' => $currentMoveOption->np_overshooting,
            'np_traverse' => $currentMoveOption,
       ]);
        
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
        $nextMoveOption->fo_damages = $this->getDamagesCopy($currentMoveOption->fo_damages);
        $shocksDamage =collection(
                $this->FoDebris->findByFoPositionIdAndGameId($nextMoveOption->fo_position_id,
                        $currentMoveOption->fo_car->game_id))->
            count();
        collection($nextMoveOption->fo_damages)->firstMatch(['type' => FoDamage::TYPE_SHOCKS])->
                wear_points += $shocksDamage;
        $nextMoveOption = $this->processCurveHandlingDamage($nextMoveOption);
        $foCar = $this->FoCars->get($currentMoveOption->fo_car_id, ['contain' => ['FoDamages']]);
        if ($nextMoveOption != null &&
                $foCar->isDamageOk(collection($nextMoveOption->fo_damages), collection([FoDamage::TYPE_TIRES]))) {
            return $nextMoveOption;
        } else {
            return null;
        }
    }
    
    private function addUniqueMoveOptions(CollectionInterface $moveOptions, $moveOptions2): ?CollectionInterface {
        if ($moveOptions->isEmpty()) {
            return collection($moveOptions2)->reject(
                    function($moveOption2) { return $moveOption2 == null; });
        }
        
        foreach ($moveOptions2 as $moveOption2) {
            $moveOptions = $this->addUniqueMoveOption($moveOptions, $moveOption2);
        }
        return $moveOptions;
    }
    
    private function addUniqueMoveOption(CollectionInterface $moveOptions, FoMoveOption $moveOption2 = null): ?CollectionInterface {
        if ($moveOption2 == null) {
            return $moveOptions;
        }
        if ($moveOptions->every(function(FoMoveOption $_moveOption) use ($moveOption2) {
            return $_moveOption->fo_position_id != $moveOption2->fo_position_id ||
                    $_moveOption->np_allowed_left != $moveOption2->np_allowed_left ||
                    $_moveOption->np_allowed_right != $moveOption2->np_allowed_right ||
                    $_moveOption->np_moves_left != $moveOption2->np_moves_left ||
                    !$this->compareDamages($_moveOption->fo_damages, $moveOption2->fo_damages);
        })) {
            return $moveOptions->appendItem($moveOption2);
        } else {
            return $moveOptions;
        }
    }
    
    private function getBrakingOption(FoMoveOption $moveOption): ?FoMoveOption {
        $originalBrakeDamage = collection($moveOption->fo_damages)->
                firstMatch(['type' => FoDamage::TYPE_BRAKES])->
                wear_points;
        $carBrakeWearPoints = $this->FoDamages->find('all')->
                where(['fo_car_id' => $moveOption->fo_car_id, 'type' => FoDamage::TYPE_BRAKES])->
                select('wear_points')->
                first()->wear_points; 
       if (($carBrakeWearPoints <= 3 && $carBrakeWearPoints <= $originalBrakeDamage + 1) ||
               $originalBrakeDamage >= 6)    //can't add another brake damage
            return null;
        
        $brakingOption = new FoMoveOption([
            'fo_car_id' => $moveOption->fo_car_id,
            'fo_car' => $moveOption->fo_car,
            'fo_position_id' => $moveOption->fo_position_id,
            'fo_curve_id' => $moveOption->fo_curve_id,
            'stops' => $moveOption->stops,
            'is_next_lap' => $moveOption->is_next_lap,
            'np_overshooting' => $moveOption->np_overshooting,
            'np_moves_left' => $moveOption->np_moves_left - 1,
            'np_allowed_left' => $moveOption->np_allowed_left,
            'np_allowed_right' => $moveOption->np_allowed_right,
            'np_traverse' => $moveOption,
       ]);
        $brakingOption->fo_damages = $this->getDamagesCopy($moveOption->fo_damages);
        collection($brakingOption->fo_damages)->
                firstMatch(['type' => FoDamage::TYPE_BRAKES])->
                wear_points++;
        return $brakingOption;
    }
    
    private function getDamagesCopy($foDamages) : array {
        $newFoDamages = collection([]);
        foreach ($foDamages as $foDamage) {
            $newFoDamages =  $newFoDamages->appendItem(new FoDamage([
                'wear_points' => $foDamage->wear_points,
                'type' => $foDamage->type,
           ]));
        }
        return $newFoDamages->toList();
    }
    
    private function getZeroDamages($damageTypes = [FoDamage::TYPE_TIRES,
                        FoDamage::TYPE_BRAKES, FoDamage::TYPE_SHOCKS]) : array {
        if ($damageTypes instanceof int) {
            return [new FoDamage([
                        'wear_points' => 0,
                        'type' => $damageTypes,
                   ])];
        } else if (is_array($damageTypes)) {
            $foDamages = [];
            foreach ($damageTypes as $damageType) {
                $foDamages[] = new FoDamage([
                        'wear_points' => 0,
                        'type' => $damageType,
                   ]);
            }
            return $foDamages;
        } else {
            throw new InvalidArgumentException();
        }
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
                collection($nextMoveOption->fo_damages)->
                        firstMatch(['type' => FoDamage::TYPE_TIRES])->
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
            collection($nextMoveOption->fo_damages)->
                    firstMatch(['type' => FoDamage::TYPE_TIRES])->
                    wear_points++;
            return $nextMoveOption;
        }
        
        return $nextMoveOption;
    }
    
    private function adjustBrakeDamage($moveOptions) {
        foreach ($moveOptions as $moveOption) {
            $foDamages = collection($moveOption->fo_damages);
            $brakeDamage = $foDamages->firstMatch(['type' => FoDamage::TYPE_BRAKES]);
            if ($brakeDamage->wear_points > 3) {
                $foDamages->firstMatch(['type' => FoDamage::TYPE_TIRES])->
                        wear_points += ($brakeDamage->wear_points - 3);
                $brakeDamage->wear_points = 3;
            }
        }
        return $moveOptions;
    }
    
    /**
     * The input params need to be sets of the same damage types, the function
     * returns true, if the $compare returns true for each pair of wear_points.
     * If no $compare function provided, it returns true if the damage sets
     * have the same wear_points.
     * 
     * @param array<FoDamage> $damages1
     * @param array<FoDamage> $damages2
     * @param callable $compare
     * @return bool
     */
    private function compareDamages($damages1, $damages2, callable $compare = null): bool {
        if ($compare == null) {
            $compare = function($a, $b) { return $a == $b; };
        }
        $damages1 = collection($damages1)->sortBy('type');
        $damages2 = collection($damages2)->sortBy('type');
        return $damages1->zip($damages2)->
                every(function($damagePair) use ($compare) {
                    return $compare($damagePair[0]->wear_points, $damagePair[1]->wear_points);
        });
    }
    
    /**
     * 
     * @param array<FoMoveOption> $moveOptions
     * @return array
     */
    private function makeUnique(CollectionInterface $moveOptions): CollectionInterface {
        for ($referenceMoveOptionIndex = $moveOptions->count() - 1;
                $referenceMoveOptionIndex >= 0;
                $referenceMoveOptionIndex = min($referenceMoveOptionIndex - 1, $moveOptions->count() - 1)) {
            $referenceMoveOption = $moveOptions->take(1, $referenceMoveOptionIndex)->first();
            $moveOptions = $moveOptions->
                reject(function(FoMoveOption $moveOption, int $moveOptionIndex)
                        use ($referenceMoveOption, $referenceMoveOptionIndex) {
                    if ($moveOption === $referenceMoveOption) {
                        return false;
                    }
                    if ($moveOption->fo_position_id != $referenceMoveOption->fo_position_id) {
                        return false;
                    }
                    if ($this->compareDamages($moveOption->fo_damages,
                            $referenceMoveOption->fo_damages,
                            function (int $testDamagePoints, int $referenceDamagePoints) {
                                return $testDamagePoints >= $referenceDamagePoints;
                            })) {
                        return true;
                    }
                    return false;
                });
        }
        return $moveOptions;
    }
}
