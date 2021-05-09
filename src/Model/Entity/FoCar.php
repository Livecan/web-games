<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Model\Entity\FoDamage;
use Cake\Collection\CollectionInterface;
use App\Model\FormulaLogic\DiceLogic;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;
use \Livecan\EntityUtility\EntitySaveTrait;
use App\Model\Entity\FoLog;

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
    use LazyLoadEntityTrait;
    use EntitySaveTrait {
        EntitySaveTrait::_repository insteadof LazyLoadEntityTrait;
    }
    
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
    
    public function getDamageByType(int $foDamageType) : FoDamage {
        return collection($this->fo_damages)->firstMatch(['type' => $foDamageType]);
    }
    
    public function assignMovementDamages(array $movementDamages) : FoCar {
        foreach ($movementDamages as $movementDamage) {
            switch ($movementDamage->type) {
                case (FoDamage::TYPE_TIRES):
                case (FoDamage::TYPE_BRAKES):
                    $this->assignDamage($movementDamage);
                    break;
                case (FoDamage::TYPE_SHOCKS):
                    $this->assignDamage($movementDamage, DiceLogic::BLACK_SHOCKS_THRESHOLD);
                    break;
            }
        }
        return $this;
    }
    
    public function assignDamage(FoDamage $damage, $badRoll = null, $log = true) : int {
        $carWear = $this->getDamageByType($damage->type);
        $totalDamageDealt = 0;
        if ($badRoll !== null) {
            for ($i = 0; $i < $damage->wear_points; $i++) {
                $roll = DiceLogic::getDiceLogic()->getRoll(0);
                (new FoLog([
                    'fo_car_id' => $this->id,
                    'roll' => $roll,
                    'damage_type' => $damage->type,
                    'type' => FoLog::TYPE_DAMAGE,
                ]))->save();
                
                if ($roll <= $badRoll) {
                    $carWear->wear_points--;
                    $totalDamageDealt++;
                }
            }
        } else {
            $totalDamageDealt = $damage->wear_points;
            $carWear->wear_points -= $totalDamageDealt;
            (new FoLog([
                'fo_car_id' => $this->id,
                'type' => FoLog::TYPE_DAMAGE,
                'fo_damages' => [
                    $damage,
                ],
            ]))->save();
        }
        
        $this->setDirty('fo_damages');
        if (!$this->isOk()) {
            $this->retire();
        }
        $this->save();
        
        return $totalDamageDealt;
    }
    
    public function shift(int $gear) {
        $gearDiff = $gear - $this->gear;
        if ($gearDiff < -1) {
            $this->processGearChangeDamage($gearDiff);
        }
        (new FoLog([
            'fo_car_id' => $this->id,
            'gear' => $gear,
            'roll' => DiceLogic::getDiceLogic()->getRoll($gear),
            'type' => FoLog::TYPE_MOVE,
        ]))->save();
        $this->gear = $gear;
        return $this->save();
    }
    
    private function processGearChangeDamage(int $gearDiff) {
        switch ($gearDiff) {
            case (-4):
                $this->assignDamage(FoDamage::getOneDamage(FoDamage::TYPE_ENGINE));
            case (-3):
                $this->assignDamage(FoDamage::getOneDamage(FoDamage::TYPE_BRAKES));
            case (-2):
                $this->assignDamage(FoDamage::getOneDamage(FoDamage::TYPE_GEARBOX));
                break;
        }
    }
    
    public function retire() {
        $this->order = null;
        $this->state = FoCar::STATE_RETIRED;
        $this->fo_position_id = null;
        return $this->save();
    }
}
