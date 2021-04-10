<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoGame Entity
 *
 * @property int $id
 * @property int $game_id
 * @property int $fo_track_id
 * @property int $cars_per_player
 * @property int $wear_points
 * @property int $laps
 * $property int $pit_rule_id
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Game $game
 * @property \App\Model\Entity\FoTrack $fo_track
 */
class FoGame extends Entity
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
        'fo_track_id' => true,
        'cars_per_player' => true,
        'wear_points' => true,
        'laps' => true,
        'pit_rule_id' => true,
        'created' => true,
        'game' => true,
        'fo_track' => true,
    ];
}
