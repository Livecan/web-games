<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoTraverse Entity
 *
 * @property int $id
 * @property int $fo_move_option_id
 * @property int $fo_position_id
 *
 * @property \App\Model\Entity\FoMoveOption $fo_move_option
 * @property \App\Model\Entity\FoPosition $fo_position
 */
class FoTraverse extends Entity
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
        'fo_move_option_id' => true,
        'fo_position_id' => true,
        'fo_move_option' => true,
        'fo_position' => true,
    ];
}
