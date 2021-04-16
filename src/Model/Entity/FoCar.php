<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FoCar Entity
 *
 * @property int $id
 * @property int $game_id
 * @property int $user_id
 * @property int $lap
 * @property int|null $fo_position_id
 * @property int $gear
 * @property int $order
 * @property int|null $fo_curve_id
 * @property int|null $stops
 * @property string $state
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Game $game
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\FoPosition $fo_position
 * @property \App\Model\Entity\FoCurve $fo_curve
 * @property \App\Model\Entity\FoDamage[] $fo_damages
 * @property \App\Model\Entity\FoLog[] $fo_logs
 * @property \App\Model\Entity\FoMoveOption[] $fo_move_options
 */
class FoCar extends Entity
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
        'lap' => true,
        'fo_position_id' => true,
        'gear' => true,
        'order' => true,
        'fo_curve_id' => true,
        'stops' => true,
        'state' => true,
        'created' => true,
        'modified' => true,
        'game' => true,
        'user' => true,
        'fo_position' => true,
        'fo_curve' =>true,
        'fo_damages' => true,
        'fo_logs' => true,
        'fo_move_options' => true,
    ];
}
