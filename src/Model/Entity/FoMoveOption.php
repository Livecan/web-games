<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Entity;

/**
 * FoMoveOption Entity
 *
 * @property int $id
 * @property int $fo_car_id
 * @property int $fo_position_id
 * @property int|null $fo_curve_id
 * @property int|null $stops
 * @property bool $is_next_lap
 * @property int $np_moves_left
 * @property int $np_allowed_left
 * @property int $np_allowed_right
 * @property bool $np_overshooting
 * @property string $np_traverse
 * @property int $np_overtaking
 *
 * @property \App\Model\Entity\FoCar $fo_car
 * @property \App\Model\Entity\FoPosition $fo_position
 * @property \App\Model\Entity\FoCurve $fo_curve
 * @property \App\Model\Entity\FoDamage[] $fo_damages
 */
class FoMoveOption extends Entity
{
    use LocatorAwareTrait;
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
        'fo_position_id' => true,
        'fo_curve_id' => true,
        'stops' => true,
        'is_next_lap' => true,
        'np_moves_left' => true,
        'np_allowed_left' => true,
        'np_allowed_right' => true,
        'np_overshooting' => true,
        'np_overtaking' => true,
        'fo_car' => true,
        'fo_position' => true,
        'fo_curve' => true,
        'fo_damages' => true,
        'np_traverse' => true,
    ];
    
    public function &__get(string $field)
    {
        if ($field == 'fo_position' && !$this->has($field)) {
            $foPosition = $this->getTableLocator()->get('FoPositions')->get($this->fo_position_id);
            if ($foPosition != null) {
                $this->set($field, $foPosition);
            }
        }
        if ($field == 'fo_car' && !$this->has($field)) {
            $foCar = $this->getTableLocator()->get('FoCars')->get($this->fo_car_id);
            if ($foCar != null) {
                $this->set($field, $foCar);
            }
        }
        return $this->get($field);
    }
}
