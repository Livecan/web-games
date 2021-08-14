<?php
namespace App\Model\Entity;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use App\Model\Entity\FoCar;
use App\Model\Entity\FoDamage;
use App\Model\Entity\FoLog;
use App\Model\FormulaLogic\DiceLogic;

trait PitstopTrait {
    use LocatorAwareTrait;

    public function isEnteringPits() {
        return $this->fo_position->team_pits == $this->team && $this->last_pit_lap != $this->lap;
    }

    public function fixCar() {
        $maxTireDamage = $this->getTableLocator()->get('FoLogs')->find('all')->
            where(['fo_car_id' => $this->id, 'type' => FoLog::TYPE_INITIAL])->
            contain(['FoDamages'  => function(Query $q) {
                return $q->where(['type' => FoDamage::TYPE_TIRES]);
            }])->
            first()->
            fo_damages[0];

        $tireDamage = $this->getDamageByType(FoDamage::TYPE_TIRES);
        $tireDamage->wear_points = $maxTireDamage->wear_points;

        $tireDamage->save();

        (new FoLog([
            'fo_car_id' => $this->id,
            'type' => FoLog::TYPE_REPAIR,
            'fo_damages' => [$tireDamage->getDamageCopy()],
        ]))->save();

        $this->last_pit_lap = $this->lap;
        $this->pits_state = FoCar::PITS_STATE_IN_PITS;
        $this->save();
    }

    public function finishPitstop(callable $moveCar) {
        if ($this->pits_state == FoCar::PITS_STATE_LONG_STOP) {
            $this->pits_state = null;
            $this->save();
            return;
        }

        $pitstopRoll = DiceLogic::getDiceLogic()->getRoll(0);
        FoLog::logRoll($this, $pitstopRoll, FoLog::TYPE_LEAVING_PITS);
        if ($pitstopRoll < DiceLogic::BLACK_LONG_PITSTOP_BOTTOM) {
            $pitstopExitLength = $this->formula_game->fo_game->fo_track->pitlane_exit_length +
                ($this->team - 1) * 2; //drivers in the pits in the back can go further
            $movesLeft = min($pitstopExitLength, (int)ceil($pitstopRoll / 2));
            $this->gear = 4;

            FoLog::logRoll($this, $movesLeft, FoLog::TYPE_MOVE);

            $this->pits_state = null;

            $this->save();

            $moveCar($this, $movesLeft);
        } else {
            //long stop
            $this->gear = 3;
            $this->order = null;
            $this->pits_state = FoCar::PITS_STATE_LONG_STOP;
            $this->save();
        }

    }
}
