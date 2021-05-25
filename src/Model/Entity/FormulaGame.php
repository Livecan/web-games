<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;
use Livecan\EntityUtility\EntitySaveTrait;
use App\Model\FormulaLogic\DiceLogic;

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
    use LocatorAwareTrait;
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
    
    public function generateCarOrder() {
        $this->fo_cars = $this->getTableLocator()->get('FoCars')->
                find('all')->
                contain(['FoPositions'])->
                where(['game_id' => $this->id, 'state' => FoCar::STATE_RACING])->
                order(['lap' => 'DESC', 'FoPositions.order' => 'DESC'])->
                toList();
        $order = 1;
        foreach ($this->fo_cars as $foCar) {
            $foCar->order = $order++;
        }
        $this->setDirty('fo_cars');
        $this->save();
        return $this->fo_cars;
    }
    
    public function getNextCar() : ?FoCar {
        return $this->getTableLocator()->get('FoCars')->
                find('all')->
                contain(['FoDamages'])->
                where(['game_id' => $this->id])->
                whereNotNull('order')->
                order(['order' => 'ASC'])->
                first();
    }
    
    public function getSavedMoveOptions() {
        return $this->getTableLocator()->get('FoMoveOptions')->find('all')->
            contain(['FoCars', 'FoPositions'])->
            contain(['FoDamages' => function(Query $q) {
                return $q->select(['fo_move_option_id', 'type', 'wear_points']);
            }])->
            where(['FoCars.game_id' => $this->id])->
            toList();
    }
    
    public function assignEngineDamages() {
        foreach ($this->fo_cars as $foCar) {
            if ($foCar->gear >= 5) {
                $foCar->assignDamage(FoDamage::getOneDamage(FoDamage::TYPE_ENGINE), DiceLogic::BLACK_ENGINE_DAMAGE_TOP);
            }
        }
    }
    
    public function getNextRanking() {
        $finishedCarsCount = $this->getTableLocator()->get('FoCars')->find('all')->
                where(['game_id' => $this->id, 'state' => FoCar::STATE_FINISHED])->
                count();
        return $finishedCarsCount + 1;
    }
}
