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
use Cake\Collection\Collection;

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
    }
    
    public function getAvailableMoves(FoCar $foCar, int $movesLeft) {
        if ($movesLeft == 0) {
            return [new FoMoveOption(['fo_car_id' => $foCar->id,
                    'fo_position_id' => $foCar->fo_position_id,
                    'fo_damages' => $this->getZeroDamages([1, 3, 6]),   //Tires, Brakes, Shocks
                ])];
        }
        
        $moveOptions = collection([FoMoveOption::getFirstMoveOption(
                $foCar->id,
                $foCar->fo_position_id,
                $movesLeft,
                $this->getZeroDamages([1, 3, 6]))]);    //Tires, Brakes, Shocks
        while ($moveOptions->some(function($_moveOption) {
                return $_moveOption->np_moves_left > 0; })) {

            //take out the first FoMoveOption from the collection and remove it from it
            $currentMoveOption = $moveOptions->first();
            $moveOptions = $moveOptions->reject(function($value, $key) { return $key === 0; });
            
            $position2Positions = $this->getNextAvailablePositions(
                    $currentMoveOption->fo_position_id,
                    $currentMoveOption->np_allowed_left,
                    $currentMoveOption->np_allowed_right);

            $nextPositionMoveOptions = collection($position2Positions)->
                    map(function($position2Positions) use ($currentMoveOption) {
                        return $this->getPlainNextMoveOption($currentMoveOption, $position2Positions);
                    })->toList();
            /*TODO: add is_adjacent in db and add all the relations that are not there yet!!!
            The is_left, is_straight, is_right and is_curve would be automatically included*/
            $moveOptions = $this->mergeMoveOptions($moveOptions, $nextPositionMoveOptions);
            $moveOptions = $this->addUniqueMoveOption($moveOptions, $this->getBrakingOption($currentMoveOption));
            $moveOptions = $moveOptions->sortBy('np_moves_left', SORT_DESC, SORT_NUMERIC);
        }
        //TODO: after this is finished, do drafting if conditions for drafting are met
        //TODO: save the final options before returning
        return debug($moveOptions->toList());
    }
    
    private function getNextAvailablePositions(int $fo_position_id, bool $is_allowed_left = true, bool $is_allowed_right = true) {
        //following returns all the possible next moves, excluding fields where other cars are
        $query = $this->FoPosition2Positions->find('all')-> 
                contain([
                    'FoPositionTo' => function(Query $q) {
                        return $q->select('id');
                    },
                    'FoPositionTo.FoCars' => function(Query $q) {
                        return  $q->select('fo_position_id');
                    },
                ])->
                select(['fo_position_to_id', 'is_left', 'is_straight', 'is_right', 'is_curve', 'is_pitlane_move'])->
                where(['fo_position_from_id' => $fo_position_id,
                    'OR' => [['is_left' => true],
                        ['is_straight' => true],
                        ['is_right' => true],
                        ['is_curve' => true],
                    ]
                ]);

        if (!$is_allowed_left) {
            $query = $query->where(['is_left' => false]);
        }
        if (!$is_allowed_right) {
            $query = $query->where(['is_right' => false]);
        }

        return collection($query)->
            filter(function(FoPosition2Position $foPosition2Position) {
                return count($foPosition2Position->fo_position_to->fo_cars) == 0;
            })->
            map(function(FoPosition2Position $foPosition2Position) {
                return $foPosition2Position->unset(['fo_position_to']);
            })->
            toList();
    }
    
    private function getPlainNextMoveOption(FoMoveOption $currentMoveOption, FoPosition2Position $nextPosition2Positions): FoMoveOption {
        $nextMoveOption = new FoMoveOption(['fo_car_id' => $currentMoveOption->fo_car_id,
            'fo_position_id' => $nextPosition2Positions->fo_position_to_id,
            'np_moves_left' => ($currentMoveOption->np_moves_left - 1),
            'np_allowed_left' => $currentMoveOption->np_allowed_left,
            'np_allowed_right' => $currentMoveOption->np_allowed_right,
        ]);
        
        if ($nextPosition2Positions->is_left || $nextPosition2Positions->is_curve) {
            $nextMoveOption->np_allowed_right = false;
        }
        if ($nextPosition2Positions->is_right || $nextPosition2Positions->is_curve) {
            $nextMoveOption->np_allowed_left = false;
        }
        //TODO: process damages, if no damages, just make a copy in ->fo_damages
        $nextMoveOption->fo_damages = $this->getNewDamages($currentMoveOption->fo_damages);
        return $nextMoveOption;
    }
    
    private function mergeMoveOptions(CollectionInterface $moveOptions, $moveOptions2) {
        if ($moveOptions->count() == 0) {
            return collection($moveOptions2);
        }
        
        foreach ($moveOptions2 as $moveOption2) {
            $moveOptions = $this->addUniqueMoveOption($moveOptions, $moveOption2);
        }
        return $moveOptions;
    }
    
    private function addUniqueMoveOption(CollectionInterface $moveOptions, FoMoveOption $moveOption) {
        if ($moveOption === null) {
            return $moveOptions;
        }
        if ($moveOptions->every(function(FoMoveOption $_moveOption) use ($moveOption) {
            return $_moveOption->fo_position_id != $moveOption->fo_position_id ||
                    $_moveOption->np_allowed_left != $moveOption->np_allowed_left ||
                    $_moveOption->np_allowed_right != $moveOption->np_allowed_right ||
                    $_moveOption->np_moves_left != $moveOption->np_moves_left; //TODO: compare also damages
        })) {
            return $moveOptions->appendItem($moveOption);
        } else {
            return $moveOptions;
        }
    }
    
    private function compareDamages($damages1, $damages2) {
        $damages1 = collection($damages1);
        $damages2 = collection($damages2);
        //TODO: compare damages
        /*collection($damages1)->every(function($damage1) use $damages2 {
            return $damages2
        })*/
    }
    
    private function getBrakingOption(FoMoveOption $moveOption) {
        $originalBrakeDamage = collection($moveOption->fo_damages)->
                firstMatch(['fo_e_damage_type_id' => 3])->
                wear_points;
        $carBrakeWearPoints = $this->FoDamages->find('all')->
                where(['fo_car_id' => $moveOption->fo_car_id, 'fo_e_damage_type_id' => 3])->
                select('wear_points')->
                first()->wear_points;
        if ($carBrakeWearPoints <= $originalBrakeDamage + 1)    //can't add another brake damage
            return null;
        
        $brakingOption = new FoMoveOption([
            'fo_car_id' => $moveOption->fo_car_id,
            'fo_position_id' => $moveOption->fo_position_id,
            'np_moves_left' => $moveOption->np_moves_left - 1,
            'np_allowed_left' => $moveOption->np_allowed_left,
            'np_allowed_right' => $moveOption->np_allowed_right,
        ]);
        $brakingOption->fo_damages = $this->getNewDamages($moveOption->fo_damages, 3);
        return $brakingOption;
    }
    
    private function getNewDamages($foDamages, $increaseDamageTypeId = null) {
        $newFoDamages = collection([]);
        foreach ($foDamages as $foDamage) {
            $wearPoints = $foDamage->wear_points;
            if ($increaseDamageTypeId != null &&
                    $increaseDamageTypeId === $foDamage->fo_e_damage_type_id) {
                $wearPoints++;
            }
            $newFoDamages =  $newFoDamages->appendItem(new FoDamage([
                    'wear_points' => $wearPoints,
                    'fo_e_damage_type_id' => $foDamage->fo_e_damage_type_id,
                ]));
        }
        return $newFoDamages->toList();
    }
    
    private function getZeroDamages($foEDamageTypeIds) {
        if ($foEDamageTypeIds instanceof int) {
            return [$this->getZeroDamage($foEDamageTypeIds)];
        } else if (is_array($foEDamageTypeIds)) {
            $foDamages = [];
            foreach ($foEDamageTypeIds as $foEDamageTypeId) {
                $foDamages[] = $this->getZeroDamage($foEDamageTypeId);
            }
            return $foDamages;
        } else {
            throw new InvalidArgumentException();
        }
    }
    
    private function getZeroDamage($foEDamageTypeId) {
        return new FoDamage([
            'wear_points' => 0,
            'fo_e_damage_type_id' => $foEDamageTypeId,
        ]);
    }
}
