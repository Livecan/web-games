<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $password
 * @property bool $is_admin
 * @property bool $is_beta
 *
 * @property \App\Model\Entity\DrTokensGame[] $dr_tokens_games
 * @property \App\Model\Entity\DrTurn[] $dr_turns
 * @property \App\Model\Entity\Game[] $games
 */
class User extends Entity
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
        'password' => true,
        'is_admin' => true,
        'is_beta' => true,
        'dr_tokens_games' => true,
        'dr_turns' => true,
        'games' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'is_admin',
        'is_beta',
    ];
    
    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }
}
