<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Game Entity
 *
 * @property int $id
 * @property string $name
 * @property int $min_players
 * @property int $max_players
 * @property int $creator_id
 * @property string $game_type_id
 * @property int @game_state_id
 *
 * @property \App\Model\Entity\User $creator
 * @property \App\Model\Entity\User[] $users
 */
class Game extends Entity
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
        'name' => true,
        'min_players' => true,
        'max_players' => true,
        'creator_id' => true,
        'game_type_id' => true,
        'game_states' => true,
        'creator' => true,
        'users' => true,
    ];
}
