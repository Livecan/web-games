<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DrTokensGame Entity
 *
 * @property int $id
 * @property int $game_id
 * @property int $dr_token_id
 * @property int|null $position
 * @property int|null $user_id
 *
 * @property \App\Model\Entity\Game $game
 * @property \App\Model\Entity\DrToken $dr_token
 * @property \App\Model\Entity\User $user
 */
class DrTokensGame extends Entity
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
        'dr_token_id' => true,
        'position' => true,
        'user_id' => true,
        'game' => true,
        'dr_token' => true,
        'user' => true,
        'dr_token_state_id' => true,
    ];
}
