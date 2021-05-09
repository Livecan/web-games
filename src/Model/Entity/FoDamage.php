<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use \Livecan\EntityUtility\EntitySaveTrait;

/**
 * FoDamage Entity
 *
 * @property int $id
 * @property int $fo_car_id
 * @property int|null $fo_move_option_id
 * @property int|null $fo_log_id
 * @property int $wear_points
 * @property int $type
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\FoCar $fo_car
 * @property \App\Model\Entity\FoMoveOption $fo_move_option
 * @property \App\Model\Entity\FoLog $fo_log
 */
class FoDamage extends Entity
{
    use EntitySaveTrait;
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
        'fo_car_id' => true,
        'fo_move_option_id' => true,
        'fo_log_id' => true,
        'wear_points' => true,
        'type' => true,
        'created' => true,
        'modified' => true,
        'fo_car' => true,
        'fo_move_option' => true,
        'fo_log' => true,
    ];
    
    const TYPE_TIRES = 1;
    const TYPE_GEARBOX = 2;
    const TYPE_BRAKES = 3;
    const TYPE_ENGINE = 4;
    const TYPE_CHASSIS = 5;
    const TYPE_SHOCKS = 6;
    private static $oneDamages = [];
    
    public function isOk() : bool {
        return self::isDamageOk($this->type, $this->wear_points);
    }
    
    public static function isDamageOk(int $ype, int $wearPoints) : bool {
        if ($wearPoints < 0) {
            return false;
        }
        if ($ype != self::TYPE_TIRES && $wearPoints < 1) {
            return false;
        }
        return true;
    }
    
    public static function getOneDamage(int $damageType) {
        if (!array_key_exists($damageType, self::$oneDamages)) {
            self::$oneDamages[$damageType] = new FoDamage([
                'wear_points' => 1,
                'type' => $damageType,
            ]);
        }
        return self::$oneDamages[$damageType];
    }
}
