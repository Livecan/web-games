<?php
declare(strict_types=1);

namespace App\Model\FormulaLogic;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use App\Model\Entity\FoMoveOption;
use App\Model\Entity\FoCar;
use App\Model\Entity\FoPosition2Position;
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
    }
    
    public function getAvailableMoves(FoCar $foCar, int $movesLeft) {
        if ($movesLeft == 0) {
            return [new FoMoveOption(['fo_car_id' => $foCar->id,
                    'fo_position_id' => $foCar->fo_position_id])
                ];
        }
        
        $moveOptions = collection([FoMoveOption::getFirstMoveOption(
                $foCar->id,
                $foCar->fo_position_id,
                $movesLeft)]);
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
            'np_allowed_right' => $currentMoveOption->np_allowed_right]);
        
        if ($nextPosition2Positions->is_left || $nextPosition2Positions->is_curve) {
            $nextMoveOption->np_allowed_right = false;
        }
        if ($nextPosition2Positions->is_right || $nextPosition2Positions->is_curve) {
            $nextMoveOption->np_allowed_left = false;
        }
        return $nextMoveOption;
    }
    
    private function mergeMoveOptions($moveOptions, $moveOptions2) {
        $moveOptions = collection($moveOptions);
        if ($moveOptions->count() === 0) {
            return collection($moveOptions2);
        }
        $moveOptionsAppend = [];
        foreach ($moveOptions2 as $moveOption2) {
            if ($moveOptions->every(function(FoMoveOption $_moveOption) use ($moveOption2) {
                return $_moveOption->fo_position_id != $moveOption2->fo_position_id ||
                        $_moveOption->np_allowed_left != $moveOption2->np_allowed_left ||
                        $_moveOption->np_allowed_right != $moveOption2->np_allowed_right ||
                        $_moveOption->np_moves_left != $moveOption2->np_moves_left; //TODO: compare also damages
            })) {
                $moveOptionsAppend[] = $moveOption2;
            }
        }
        $moveOptions = $moveOptions->append($moveOptionsAppend);
        return $moveOptions;
    }
}
