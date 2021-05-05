<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FormulaGame Entity
 *
 * @property int $id
 * @property string $name
 * @property int $min_players
 * @property int $max_players
 * @property int $creator_id
 * @property int $game_state_id
 * @property int $game_type_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $creator
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\GameState $game_state
 * @property \App\Model\Entity\GameType $game_type
 * @property \App\Model\Entity\DrResult[] $dr_results
 * @property \App\Model\Entity\DrTurn[] $dr_turns
 * @property \App\Model\Entity\FoCar[] $fo_cars
 * @property \App\Model\Entity\FoDebri[] $fo_debris
 * @property \App\Model\Entity\FoGame $fo_game
 * @property \App\Model\Entity\DrToken[] $dr_tokens
 */
class FormulaGame extends Entity
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
        'game_state_id' => true,
        'game_type_id' => true,
        'created' => true,
        'modified' => true,
        'creator' => true,
        'users' => true,
        'game_state' => true,
        'game_type' => true,
        'fo_cars' => true,
        'fo_debris' => true,
        'fo_game' => true,
    ];
}
