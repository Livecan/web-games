<?php

declare(strict_types=1);

namespace App\Model\FormulaLogic;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use App\Model\Entity\FoCar;
use App\Model\Entity\FoDamage;
use App\Model\Entity\FoLog;
use App\Model\FormulaLogic\DiceLogic;
use Cake\Database\Expression\QueryExpression;

/**
 * Description of MovementLogic
 *
 * @author roman
 */
class PitstopLogic {
    use LocatorAwareTrait;
    
    public function __construct() {
        $this->FoLogs = $this->getTableLocator()->get('FoLogs');
    }
    
    public function fixCar(FoCar $foCar) {
        $maxTireDamage = $this->FoLogs->find('all')->
            where(['fo_car_id' => $foCar->id, 'type' => FoLog::TYPE_INITIAL])->
            contain(['FoDamages'  => function(Query $q) {
                return $q->where(['type' => FoDamage::TYPE_TIRES]);
            }])->
            first()->
            fo_damages[0];
        
        $tireDamage = $foCar->getDamageByType(FoDamage::TYPE_TIRES);
        $tireDamage->wear_points = $maxTireDamage->wear_points;
        
        $tireDamage->save();
        
        (new FoLog([
            'fo_car_id' => $foCar->id,
            'type' => FoLog::TYPE_REPAIR,
            'fo_damages' => [$tireDamage->getDamageCopy()],
        ]))->save();
        
        $foCar->last_pit_lap = $foCar->lap;
        $foCar->save();
    }
    
    public function finishPitstop(FoCar $foCar, callable $moveCar) {
        $pitstopRoll = DiceLogic::getDiceLogic()->getRoll(0);
        FoLog::logRoll($foCar, $pitstopRoll, FoLog::TYPE_LEAVING_PITS);
        if ($pitstopRoll < DiceLogic::BLACK_LONG_PITSTOP_BOTTOM) {
            $pitstopExitLength = $foCar->formula_game->fo_game->fo_track->pitlane_exit_length +
                ($foCar->team - 1) * 2; //driders in the pits in the back can go further
            $movesLeft = min($pitstopExitLength, (int)ceil($pitstopRoll / 2));
            $foCar->gear = 4;
            
            FoLog::logRoll($foCar, $movesLeft, FoLog::TYPE_MOVE);
            
            $foCar->save();
            
            $moveCar($foCar, $movesLeft);
        } else {
            //long stop
            $foCar->gear = 3;
            $foCar->save();
        }
        
    }
}
