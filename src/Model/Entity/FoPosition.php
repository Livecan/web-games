<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Collection\CollectionInterface;

/**
 * FoPosition Entity
 *
 * @property int $id
 * @property int $fo_track_id
 * @property int $order
 * @property int|null $fo_curve_id
 * @property bool $is_finish
 * @property int|null $starting_position
 * @property int|null $team_pits If the field is pit box, it contains a number 1-5 matching number in FoCar->team, else null.
 * @property int $pos_x
 * @property int $pos_y
 *
 * @property \App\Model\Entity\FoTrack $fo_track
 * @property \App\Model\Entity\FoCurve $fo_curve
 * @property \App\Model\Entity\FoCar[] $fo_cars
 * @property \App\Model\Entity\FoDebri[] $fo_debris
 * @property \App\Model\Entity\FoLog[] $fo_logs
 * @property \App\Model\Entity\FoMoveOption[] $fo_move_options
 * @property \App\Model\Entity\FoPosition2Position $fo_position2_positions_from
 * @property \App\Model\Entity\FoPosition2Position $fo_position2_positions_to
 */
class FoPosition extends Entity
{
    use LocatorAwareTrait;
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'fo_track_id' => true,
        'order' => true,
        'fo_curve_id' => true,
        'is_finish' => true,
        'starting_position' => true,
        'team_pits' => true,
        'pos_x' => true,
        'pos_y' => true,
        'fo_track' => true,
        'fo_curve' => true,
        'fo_cars' => true,
        'fo_debris' => true,
        'fo_logs' => true,
        'fo_move_options' => true,
        'fo_position2_positions_from' => true,
        'fo_position2_positions_to' => true,
    ];
    
    /**
     * Returns a list of next positions within Position2Position objects
     * excluding positions where other cars are.
     * //TODO: refactor - could return straight Position objects instead of encapsulating?
     * 
     * @param int $gameId
     * @param bool $allowedLeft
     * @param bool $allowedRight
     * @param bool $overtaking
     * @return CollectionInterface
     */
    public function getNextAvailablePositions(int $gameId, bool $allowedLeft,
            bool $allowedRight, bool $overtaking) : CollectionInterface {
        //following returns all the possible next moves, excluding fields where other cars are
        $query = $this->getTableLocator()->get('FoPosition2Positions')->find('all')-> 
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
            select(['fo_position_to_id', 'is_left', 'is_straight', 'is_right',
                'is_curve', 'is_pitlane_move'])->
            where(['fo_position_from_id' => $this->id,
                'OR' => [['is_left' => true],
                    ['is_straight' => true],
                    ['is_right' => true],
                    ['is_curve' => true],
               ]
           ]);

        if (!$allowedLeft && !$overtaking) {
            $query = $query->where(['is_left' => false]);
        }
        if (!$allowedRight && !$overtaking) {
            $query = $query->where(['is_right' => false]);
        }

        return collection($query)->
            filter(function(FoPosition2Position $foPosition2Position) {
                return count($foPosition2Position->fo_position_to->fo_cars) == 0;
            })->
            buffered();
    }
    
    /**
     * Returns true if the current position has a next position in pit lane.
     * 
     * @return bool
     */
    public function hasPitlaneOption() : bool {
        return $this->getTableLocator()->get('FoPosition2Positions')->find('all')->
            where(['fo_position_from_id' => $this->id,
                'is_pitlane_move' => true])->
            first() != null;
    }
    
    /**
     * Returns a list of next positions that are in pit lane - usually just
     * one position, excluding positions taken over by other cars.
     * 
     * @param int $gameId
     * @return CollectionInterface
     */
    public function getNextPitlanePosition(int $gameId) : ?FoPosition {
        $nextPosition2PositionWithCars = $this->getTableLocator()->get('FoPosition2Positions')->find('all')->
            where(['fo_position_from_id' => $this->id,
                'is_pitlane_move' => true])->
            contain([
                'FoPositionTo' => function(Query $q) {
                    return $q->select(['id', 'is_finish', 'team_pits']);
                },
                'FoPositionTo.FoCars' => function(Query $q) use ($gameId) {
                    return  $q->where(['game_id' => $gameId])->
                            select('fo_position_id');
                }
            ])->first();
        if ($nextPosition2PositionWithCars == null) {
            return null;
        }
        $positionTo = $nextPosition2PositionWithCars->fo_position_to;
        if (count($positionTo->fo_cars) > 0) {
            return null;
        }
        return $positionTo;
    }
}
