<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoDebri Entity
 *
 * @property int $id
 * @property int $game_id
 * @property int $fo_position_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Game $game
 * @property \App\Model\Entity\FoPosition $fo_position
 */
class FoDebri extends Entity
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
        'game_id' => true,
        'fo_position_id' => true,
        'created' => true,
        'modified' => true,
        'game' => true,
        'fo_position' => true,
    ];
}
