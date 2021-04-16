<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GamesUser Entity
 *
 * @property int $id
 * @property int $game_id
 * @property int $user_id
 * @property string $ready_state
 * @property int|null $order_number
 * @property int|null $next_user_id
 *
 * @property \App\Model\Entity\Game $game
 * @property \App\Model\Entity\User $user
 */
class GamesUser extends Entity
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
        'ready_state' => true,
        'order_number' => true,
        'next_user_id' => true,
        'game' => true,
        'user' => true,
    ];
}
