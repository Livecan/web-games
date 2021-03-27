<?php
declare(strict_types=1);

namespace App\Model\Logic;

use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Description of FormulaLogic
 *
 * @author roman
 */
class FormulaLogic {
    use LocatorAwareTrait;
    
    public function __construct() {
        $this->FoPositions = $this->getTableLocator()->get('FoPositions');
        $this->FoCars = $this->getTableLocator()->get('FoCars');
        $this->FoDamages = $this->getTableLocator()->get('FoDamages');
    }
    
    public function start($formulaGame) {
        
        $formulaGame->fo_cars = collection($formulaGame->users)->map(function($user) use ($formulaGame) {
            $playerCarsLeft = $formulaGame->fo_game->cars_per_player;
            $cars = [];
            while ($playerCarsLeft-- > 0) {
                $damages = $this->FoDamages->newEntities([
                        ['fo_e_damage_type_id' => 1, 'wear_points' => 7],
                        ['fo_e_damage_type_id' => 2, 'wear_points' => 3],
                        ['fo_e_damage_type_id' => 3, 'wear_points' => 3],
                        ['fo_e_damage_type_id' => 4, 'wear_points' => 3],
                        ['fo_e_damage_type_id' => 5, 'wear_points' => 3],
                        ['fo_e_damage_type_id' => 6, 'wear_points' => 3],]);
                $car = $this->FoCars->createUserCar($formulaGame->id, $user->id, $damages, false);
                $cars[] = $car;
            }
            return $cars;
        })->unfold()->shuffle();
        
        $startingPositions = $this->FoPositions->find('all')->
                select('id')->
                where(['fo_track_id', $formulaGame->fo_game->track_id])->
                whereNotNull('starting_position')->
                order(['starting_position' => 'ASC']);
        $formulaGame->fo_cars = $formulaGame->fo_cars->zip($startingPositions)->
                map(function($carPositionPair) {
                    $carPositionPair[0]->fo_position_id = $carPositionPair[1]->id;
                    return $carPositionPair[0];
                });
        $formulaGame->fo_cars =
                $this->FoCars->saveMany($formulaGame->fo_cars->toList(),
                        ['associated' => ['FoDamages']]);
    }
}
