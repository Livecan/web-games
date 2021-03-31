<?php
declare(strict_types=1);

namespace App\Model\FormulaLogic;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use App\Model\Entity\FoMoveOption;
use App\Model\Entity\FoCar;
use App\Model\Entity\FoPosition2Position;

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

            $currentMoveOption = $moveOptions->first();
            $moveOptions = $moveOptions->reject(function($value, $key) { return $key === 0; });
            
            $position2Positions = $this->getNextAvailablePositions($currentMoveOption->fo_position_id);

            foreach ($position2Positions as $position2position) {
                //TODO: traversing and all the crazy conditions
            }
            /*TODO: add is_adjacent in db and add all the relations that are not there yet!!!
            The is_left, is_straight, is_right and is_curve would be automatically included*/
            $moveOptions = $moveOptions->sortBy('np_moves_left', SORT_DESC, SORT_NUMERIC);
        }
        //TODO: after this is finished, do drafting if conditions for drafting are met
        //TODO: save the final options before returning
        return $moveOptions->toList();
    }
    
    public function getNextAvailablePositions(int $fo_position_id) {
        return collection(   //returns all the possible next moves, excluding fields where other cars are
            $this->FoPosition2Positions->find('all')->
                contain([
                    'FoPositionTo' => function(Query $q) {
                        return $q->select('id');
                    },
                    'FoPositionTo.FoCars' => function(Query $q) {
                        return  $q->select('fo_position_id');
                    },
                ])->
                select(['fo_position_to_id', 'is_left', 'is_straight', 'is_right', 'is_curve', 'is_pitlane_move'])->
                where(['fo_position_from_id' => $fo_position_id]))->
            filter(function(FoPosition2Position $foPosition2Position) {
                return count($foPosition2Position->fo_position_to->fo_cars) == 0;
            })->
            map(function(FoPosition2Position $foPosition2Position) {
                return $foPosition2Position->unset(['fo_position_to']);
            })->
            toList();
    }
}
