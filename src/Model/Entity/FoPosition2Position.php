<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoPosition2Position Entity
 *
 * @property int $id
 * @property int $fo_position_from_id
 * @property int $fo_position_to_id
 * @property bool $is_left
 * @property bool $is_straight
 * @property bool $is_right
 * @property bool $is_curve
 * @property bool $is_equal_distance
 * @property bool $is_pitlane_move
 * 
 * @property \App\Model\Entity\FoPosition $fo_position_from
 * @property \App\Model\Entity\FoPosition $fo_position_to
 */
class FoPosition2Position extends Entity
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
        'fo_position_from_id' => true,
        'fo_position_to_id' => true,
        'is_left' => true,
        'is_straight' => true,
        'is_right' => true,
        'is_curve' => true,
        'is_equal_distance' => true,
        'is_pitlane_move' => true,
        'fo_position_from' => true,
        'fo_position_to' => true,
    ];
}
