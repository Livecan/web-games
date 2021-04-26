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
    
    public function getAvailableMoves(FoCar $foCar, int $movesLeft) {
        if ($movesLeft == 0) {
            return [new FoMoveOption(['fo_car_id' => $foCar->id,
                    'fo_position_id' => $foCar->fo_position_id,
                    'is_next_lap' => false,
                    'fo_damages' => $this->getZeroDamages([1, 3, 6],),   //Tires, Brakes, Shocks
                ])];
        }
        $savedMoveOptions = $this->FoMoveOptions->find('all')->
                contain(['FoCars', 'FoPositions'])->
                contain(['FoDamages' => function(Query $q) {
                    return $q->select(['fo_move_option_id', 'type', 'wear_points']);
                }])->
                where(['FoCars.game_id' => $foCar->game_id])->
                select($this->FoPositions)->
                select($this->FoMoveOptions)->
                toList();
        if (count($savedMoveOptions) > 0) {
            return $savedMoveOptions;
        }
        $moveOptions = collection([$this->FoMoveOptions->getFirstMoveOption(
                $foCar->id,
                $foCar->fo_position_id,
                $movesLeft,
                $this->getZeroDamages([1, 3, 6]))]);    //Tires, Brakes, Shocks
        while ($moveOptions->some(function($_moveOption) {
                return $_moveOption->np_moves_left > 0; })) {

            //take out the first FoMoveOption from the collection and remove it from it
            $currentMoveOption = $moveOptions->first();
            $moveOptions = $moveOptions->reject(function($value, $key) { return $key === 0; });
            
            $overtaking = $this->canOvertake($foCar->game_id, $currentMoveOption) ? 3 : $currentMoveOption->np_overtaking;
            $position2Positions = $this->getNextAvailablePositions(
                    $foCar->game_id,
                    $currentMoveOption,
                    $overtaking > 0);

            $nextPositionMoveOptions = collection($position2Positions)->
                    map(function($position2Positions) use ($currentMoveOption, $overtaking) {
                        return $this->getNextPositionMoveOption($currentMoveOption, $position2Positions, $overtaking);
                    })->toList();
            $moveOptions = $this->mergeMoveOptions($moveOptions, $nextPositionMoveOptions);
            $moveOptions = $this->addUniqueMoveOption($moveOptions, $this->getBrakingOption($currentMoveOption));
            $moveOptions = $moveOptions->sortBy('np_moves_left', SORT_DESC, SORT_NUMERIC);
        }
        //TODO: after this is finished, do drafting if conditions for drafting are met
        $moveOptions = $this->adjustBrakeDamage($moveOptions);
        
        $moveOptions = $moveOptions->filter(function($moveOption) {
            return $this->isCarDamageOK($moveOption);
        });
        
        /*debug($moveOptions->reduce(function($accumulated, FoMoveOption $moveOption) {
            return $accumulated . $moveOption->fo_position_id . ": " .
                    collection($moveOption->fo_damages)->reduce(function($accumulated, FoDamage $foDamage) {
                        return $accumulated . "\t" . $foDamage->wear_points . ",";
                    }, "") . "\n";
        }, ""));*/
        
        //debug($moveOptions->first()->np_traverse->np_traverse->np_traverse->np_traverse);
        
        $moveOptions = $this->unique($moveOptions);
        
        $this->FoMoveOptions->saveMany($moveOptions, ['associated' => ['FoDamages']]);
        
        $moveOptions->each(function(FoMoveOption $moveOption) {
            unset($moveOption->np_traverse);
            unset($moveOption->np_allowed_left);
            unset($moveOption->np_allowed_right);
            unset($moveOption->np_moves_left);
            unset($moveOption->np_overshooting);
            unset($moveOption->np_overtaking);
            $moveOption->stops++;
            $moveOption->fo_position =
                    $this->FoPositions->get($moveOption->fo_position_id);
        });
        return $moveOptions->toList();
    }
    
    private function getNextAvailablePositions(int $game_id, FoMoveOption $moveOption, bool $overtaking) {
        //following returns all the possible next moves, excluding fields where other cars are
        $query = $this->FoPosition2Positions->find('all')-> 
                contain([
                    'FoPositionFrom' => function(Query $q) {
                        return $q->select(['id', 'is_finish']);
                    },
                    'FoPositionTo' => function(Query $q) {
                        return $q->select(['id', 'is_finish']);
                    },
                    'FoPositionTo.FoCars' => function(Query $q) use ($game_id) {
                        return  $q->where(['game_id' => $game_id])->
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
            toList();
    }
    
    private function canOvertake(int $gameId, FoMoveOption $moveOption): bool {
        $foPosition2PositionStraight = $this->FoPosition2Positions->find('all')->
                contain(['FoPositionTo.FoCars' => function(Query $q) use ($gameId) {
                    return $q->select('FoCars.fo_position_id')
                            ->where(['FoCars.game_id' => $gameId]);
                }])->
                where(['fo_position_from_id' => $moveOption->fo_position_id,
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
                $this->FoDebris->findByFoPositionId($nextMoveOption->fo_position_id))->
            count();
        collection($nextMoveOption->fo_damages)->firstMatch(['type' => FoDamage::TYPE_SHOCKS])->
                wear_points += $shocksDamage;
        $nextMoveOption = $this->processCurveHandlingDamage($nextMoveOption);
        if ($nextMoveOption != null && $this->isCarDamageOK($nextMoveOption)) {
            return $nextMoveOption;
        } else {
            return null;
        }
    }
    
    private function mergeMoveOptions(CollectionInterface $moveOptions, $moveOptions2): ?CollectionInterface {
        if ($moveOptions->count() == 0) {
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
                firstMatch(['type' => FoDamage::TYPE_BRAKES])->  //increasing brakes damage
                wear_points++;
        return $brakingOption;
    }
    
    private function getDamagesCopy($foDamages) {
        $newFoDamages = collection([]);
        foreach ($foDamages as $foDamage) {
            $wearPoints = $foDamage->wear_points;
            $newFoDamages =  $newFoDamages->appendItem(new FoDamage([
                'fo_car_id' => $foDamage->fo_car_id,
                'wear_points' => $wearPoints,
                'type' => $foDamage->type,
            ]));
        }
        return $newFoDamages->toList();
    }
    
    private function getZeroDamages($damageTypes) {
        if ($damageTypes instanceof int) {
            return [$this->getZeroDamage($damageTypes)];
        } else if (is_array($damageTypes)) {
            $foDamages = [];
            foreach ($damageTypes as $damageType) {
                $foDamages[] = $this->getZeroDamage($damageType);
            }
            return $foDamages;
        } else {
            throw new InvalidArgumentException();
        }
    }
    
    private function getZeroDamage(int $damageType): FoDamage {
        return new FoDamage([
            'wear_points' => 0,
            'type' => $damageType,
        ]);
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
                        firstMatch(['type' => FoDamage::TYPE_TIRES])->   //tires damage
                        wear_points++;
                return $nextMoveOption;
            }
            //otherwise overshooting by more than one stop - invalid MoveOption
            return null;
        }
        
        //when overshooting the second curve within one turn - ivalid MoveOption
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
                    firstMatch(['type' => FoDamage::TYPE_TIRES])->   //tires damage
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
    
    private function isCarDamageOK(FoMoveOption $foMoveOption): bool {
        $foCarDamages = collection($this->FoCars->get($foMoveOption->fo_car_id,
                ['contain' => ['FoDamages']])
                ->fo_damages);
        
        foreach ($foMoveOption->fo_damages as $foDamage) {
            $damageType = $foDamage->type;
            $carDamageWearPoints = $foCarDamages->
                    firstMatch(['type' => $damageType])->wear_points;
            if ($damageType == FoDamage::TYPE_TIRES && $carDamageWearPoints - $foDamage->wear_points < 0) {
                //tire damage can drop to 0
                return false;
            }
            if ($damageType != FoDamage::TYPE_TIRES && $damageType != FoDamage::TYPE_SHOCKS
                    && $carDamageWearPoints - $foDamage->wear_points <= 0) {
                //other damages must not go under 0, but shocks are rolled
                return false;
            }
        }
        
        return true;
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
    public function compareDamages($damages1, $damages2, callable $compare = null): bool {
        if ($compare == null) {
            $compare = function($a, $b) { return $a == $b; };
        }
        $damages1 = collection($damages1)->sortBy('type');
        $damages2 = collection($damages2)->sortBy('type');
        return $damages1->zip($damages2)->every(function($damagePair) use ($compare) {
            return $compare($damagePair[0]->wear_points, $damagePair[1]->wear_points);
        });
    }
    
    private static function isHigherOrEqualDamage(FoDamage $testDamage, FoDamage $referenceDamage) {
        return $testDamage->wear_points >= $referenceDamage->wear_points;
    }
    
    /**
     * 
     * @param array<FoMoveOption> $moveOptions
     * @return array
     */
    private function unique(CollectionInterface $moveOptions): CollectionInterface {
        foreach ($moveOptions->toList() as $referenceMoveOption) {
            $moveOptions = $moveOptions->
                reject(function(FoMoveOption $moveOption) use ($referenceMoveOption) {
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
