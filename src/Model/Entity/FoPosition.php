<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoPosition Entity
 *
 * @property int $id
 * @property int $fo_track_id
 * @property int $order
 * @property int|null $fo_curve_id
 * @property bool $is_finish
 * @property int|null $starting_position
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
}
