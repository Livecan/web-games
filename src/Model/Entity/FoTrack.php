<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoTrack Entity
 *
 * @property int $id
 * @property string $name
 * @property string $game_plan
 *
 * @property \App\Model\Entity\FoCurve[] $fo_curves
 * @property \App\Model\Entity\FoGame[] $fo_games
 * @property \App\Model\Entity\FoPosition[] $fo_positions
 */
class FoTrack extends Entity
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
        'game_plan' => true,
        'fo_curves' => true,
        'fo_games' => true,
        'fo_positions' => true,
    ];
}
