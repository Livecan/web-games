<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Game Entity
 *
 * @property int $id
 * @property string $name
 * @property string $type
 *
 * @property \App\Model\Entity\DrTurn[] $dr_turns
 * @property \App\Model\Entity\DrToken[] $dr_tokens
 * @property \App\Model\Entity\Player[] $players
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
        'type' => true,
        'dr_turns' => true,
        'dr_tokens' => true,
        'users' => true,
    ];
}
