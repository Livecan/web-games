<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoCurve Entity
 *
 * @property int $id
 * @property int $fo_track_id
 * @property int|null $fo_next_curve_id
 * @property int $stops
 * @property string|null $name
 *
 * @property \App\Model\Entity\FoTrack $fo_track
 * @property \App\Model\Entity\FoCurve $fo_curve
 * @property \App\Model\Entity\FoPosition[] $fo_positions
 */
class FoCurve extends Entity
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
        'fo_next_curve_id' => true,
        'stops' => true,
        'name' => true,
        'fo_track' => true,
        'fo_curve' => true,
        'fo_positions' => true,
    ];
}
