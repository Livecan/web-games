<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Query;
use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;
use App\Model\Entity\FoDamageTrait;
use Cake\Collection\CollectionInterface;
use Cake\ORM\Locator\LocatorAwareTrait;

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
 * @property bool $np_slipstream_checked
 * @property bool $np_is_slipstreaming
 * @property bool $np_drafted_in_curve
 *
 * @property \App\Model\Entity\FoCar $fo_car
 * @property \App\Model\Entity\FoPosition $fo_position
 * @property \App\Model\Entity\FoCurve $fo_curve
 * @property \App\Model\Entity\FoDamage[] $fo_damages
 */
class FoMoveOption extends Entity
{
    use LocatorAwareTrait;
    use LazyLoadEntityTrait;
    use FoDamageTrait;
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
        'np_slipstream_checked' => true,
        'np_is_slipstreaming' => true,
        'np_drafted_in_curve' => true,
    ];    
    
    public static function getFirstMoveOption(FoCar $foCar, int $movesLeft, $foDamages)
            : self {
        return new FoMoveOption(['fo_car_id' => $foCar->id,
            'fo_car' => $foCar,
            'fo_position_id' => $foCar->fo_position_id,
            'fo_curve_id' => $foCar->fo_curve_id,
            'stops' => $foCar->stops,
            'is_next_lap' => false,
            'np_moves_left' => $movesLeft,
            'np_allowed_left' => true,
            'np_allowed_right' => true,
            'np_overshooting' => false,
            'fo_damages' => $foDamages,
            'np_traverse' => null,
            ]);
    }
    
    public function adjustBrakeDamage() : self {
        $foDamages = collection($this->fo_damages);
        $brakeDamage = $this->getDamageByType(FoDamage::TYPE_BRAKES);
        if ($brakeDamage->wear_points > 3) {
            $this->getDamageByType(FoDamage::TYPE_TIRES)->wear_points += ($brakeDamage->wear_points - 3);
            $brakeDamage->wear_points = 3;
        }
        return $this;
    }
    
    /**
     * The input params need to be sets of the same damage types, the function
     * returns true, if the $compare returns true for each pair of wear_points.
     * If no $compare function provided, it returns true if the damage sets
     * have the same wear_points.
     * 
     * @param array<FoDamage> $damages1
     * @param array<FoDamage> $damages2
     * @param callable $compare
     * @return bool
     */
    private static function compareDamages($damages1, $damages2, callable $compare = null): bool {
        if ($compare == null) {
            $compare = function($a, $b) { return $a == $b; };
        }
        $damages1 = collection($damages1)->sortBy('type');
        $damages2 = collection($damages2)->sortBy('type');
        return $damages1->zip($damages2)->
                every(function($damagePair) use ($compare) {
                    return $compare($damagePair[0]->wear_points, $damagePair[1]->wear_points);
        });
    }
    
    /**
     * 
     * @param array<FoMoveOption> $moveOptions
     * @return array
     */
    public static function makeUnique(CollectionInterface $moveOptions): CollectionInterface {
        for ($referenceMoveOptionIndex = $moveOptions->count() - 1;
                $referenceMoveOptionIndex >= 0;
                $referenceMoveOptionIndex = min($referenceMoveOptionIndex - 1, $moveOptions->count() - 1)) {
            $referenceMoveOption = $moveOptions->take(1, $referenceMoveOptionIndex)->first();
            $moveOptions = $moveOptions->
                reject(function(FoMoveOption $moveOption, int $moveOptionIndex)
                        use ($referenceMoveOption, $referenceMoveOptionIndex) {
                    if ($moveOption === $referenceMoveOption) {
                        return false;
                    }
                    if ($moveOption->fo_position_id != $referenceMoveOption->fo_position_id) {
                        return false;
                    }
                    if (self::compareDamages($moveOption->fo_damages,
                            $referenceMoveOption->fo_damages,
                            function (int $testDamagePoints, int $referenceDamagePoints) {
                                return $testDamagePoints >= $referenceDamagePoints;
                            })) {
                        return true;
                    }
                    return false;
                });
        }
        return $moveOptions;
    }
    
    public static function addUniqueMoveOption(CollectionInterface $moveOptions, FoMoveOption $moveOption2 = null): ?CollectionInterface {
        if ($moveOption2 == null) {
            return $moveOptions;
        }
        if ($moveOptions->every(function(FoMoveOption $_moveOption) use ($moveOption2) {
            return $_moveOption->fo_position_id != $moveOption2->fo_position_id ||
                    $_moveOption->np_allowed_left != $moveOption2->np_allowed_left ||
                    $_moveOption->np_allowed_right != $moveOption2->np_allowed_right ||
                    $_moveOption->np_moves_left != $moveOption2->np_moves_left ||
                    !self::compareDamages($_moveOption->fo_damages, $moveOption2->fo_damages);
        })) {
            return $moveOptions->appendItem($moveOption2);
        } else {
            return $moveOptions;
        }
    }
    
    public function canSlipstream() {
        $this->np_slipstream_checked = true;
        
        //a car is supposed to reach the position for slipstreaming without braking
        if ($this->getDamageByType(FoDamage::TYPE_BRAKES)->wear_points > 0) {
            return false;
        }
        $carInFront = array_pop($this->getTableLocator()->get('FoPosition2Positions')->find('all')->
                contain(['FoPositionTo.FoCars' => function(Query $q) {
                    return $q->where(['FoCars.game_id' => $this->fo_car->game_id]);
                }])->
                where(['fo_position_from_id' => $this->fo_position_id,
                    'is_straight' => true])->first()->fo_position_to->fo_cars ?? []);
        //there must be a car in the front
        if ($carInFront == null) {
            return false;
        }
        //both cars must be at least in gear 4
        if ($carInFront->gear < 4 || $this->fo_car->gear < 4) {
            return false;
        }
        //the slipstreaming car can't be slower than the car in front
        if ($this->fo_car->gear < $carInFront->gear) {
            return false;
        }
        return true;
    }
    
    public function getSlipstreamOption() : self {
        $slipstreamMoveOption = clone $this;
        $slipstreamMoveOption->fo_damages = FoDamage::getDamagesCopy($this->fo_damages);
        $slipstreamMoveOption->np_traverse = $this;
        $slipstreamMoveOption->np_overtaking = 3;
        $slipstreamMoveOption->np_slipstream_checked = false;
        $slipstreamMoveOption->np_moves_left = 3;
        $slipstreamMoveOption->np_is_slipstreaming = true;
        return $slipstreamMoveOption;
    }
}
