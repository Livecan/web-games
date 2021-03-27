<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoLog Entity
 *
 * @property int $id
 * @property int $fo_car_id
 * @property int|null $fo_position_id
 * @property int|null $gear
 * @property int|null $roll
 * @property int|null $ranking
 * @property string $type
 *
 * @property \App\Model\Entity\FoCar $fo_car
 * @property \App\Model\Entity\FoPosition $fo_position
 * @property \App\Model\Entity\FoDamage[] $fo_damages
 */
class FoLog extends Entity
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
        'fo_car_id' => true,
        'fo_position_id' => true,
        'gear' => true,
        'roll' => true,
        'ranking' => true,
        'type' => true,
        'fo_car' => true,
        'fo_position' => true,
        'fo_damages' => true,
    ];
}
