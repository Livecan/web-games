<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoMoveOption Entity
 *
 * @property int $id
 * @property int $fo_car_id
 * @property int $fo_position_id
 * @property int $np_moves_left
 * @property int $np_allowed_left
 * @property int $np_allowed_right
 *
 * @property \App\Model\Entity\FoCar $fo_car
 * @property \App\Model\Entity\FoPosition $fo_position
 * @property \App\Model\Entity\FoDamage[] $fo_damages
 */
class FoMoveOption extends Entity
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
        'np_moves_left' => true,
        'np_allowed_left' => true,
        'np_allowed_right' => true,    
        'fo_car' => true,
        'fo_position' => true,
        'fo_damages' => true,
    ];
    
    public static function getFirstMoveOption(int $fo_car_id, int $fo_position_id, int $movesLeft, $foDamages)
            : FoMoveOption {
        return new FoMoveOption(['fo_car_id' => $fo_car_id,
            'fo_position_id' => $fo_position_id,
            'np_moves_left' => $movesLeft,
            'np_allowed_left' => true,
            'np_allowed_right' => true,
            'fo_damages' => $foDamages,
            ]);
    }
}
