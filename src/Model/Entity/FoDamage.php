<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoDamage Entity
 *
 * @property int $id
 * @property int $fo_car_id
 * @property int|null $fo_move_option_id
 * @property int|null $fo_log_id
 * @property int $wear_points
 * @property int $fo_e_damage_type_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\FoCar $fo_car
 * @property \App\Model\Entity\FoMoveOption $fo_move_option
 * @property \App\Model\Entity\FoLog $fo_log
 * @property \App\Model\Entity\FoEDamageType $fo_e_damage_type
 */
class FoDamage extends Entity
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
        'fo_move_option_id' => true,
        'fo_log_id' => true,
        'wear_points' => true,
        'fo_e_damage_type_id' => true,
        'created' => true,
        'modified' => true,
        'fo_car' => true,
        'fo_move_option' => true,
        'fo_log' => true,
        'fo_e_damage_type' => true,
    ];
}
