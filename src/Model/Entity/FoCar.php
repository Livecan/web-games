<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Entity;
use App\Model\Entity\FoDamage;
use Cake\Collection\CollectionInterface;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

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
    use LocatorAwareTrait;
    use LazyLoadEntityTrait;
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
    
    const STATE_NOT_READY = 'N';
    const STATE_RACING = 'R';
    const STATE_RETIRED = 'X';
    const STATE_FINISHED = 'F';

    public function isOk() : bool {
        foreach ($this->fo_damages as $foDamage) {
            if (!$foDamage->isOk()) {
                return false;
            }
        }
        return true;
    }
    
    public function isDamageOk(CollectionInterface $foDamages, CollectionInterface $foDamageTypes = null) : bool {
        if ($foDamageTypes != null) {
            $foDamages = $foDamages->
                    filter(function(FoDamage $foDamage) use ($foDamageTypes) {
                        return $foDamageTypes->contains($foDamage->type);
                    });
        }
        
        foreach ($this->fo_damages as $foCarDamage) {
            $matchingDamage = $foDamages->firstMatch(['type' => $foCarDamage->type]);
            if ($matchingDamage == null) {
                continue;
            }
            if (!FoDamage::isDamageOk($foCarDamage->type, $foCarDamage->wear_points - $matchingDamage->wear_points)) {
                return false;
            }
        }
        
        return true;
    }
}
