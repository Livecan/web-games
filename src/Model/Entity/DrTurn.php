<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DrTurn Entity
 *
 * @property int $id
 * @property int $game_id
 * @property int $user_id
 * @property int $position
 * @property int $round
 * @property int $roll
 * @property bool $returning
 * @property bool $taking
 *
 * @property \App\Model\Entity\Game $game
 * @property \App\Model\Entity\Player $player
 */
class DrTurn extends Entity
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
        'user_id' => true,
        'position' => true,
        'round' => true,
        'roll' => true,
        'returning' => true,
        'taking' => true,
        'game' => true,
        'player' => true,
    ];
}
