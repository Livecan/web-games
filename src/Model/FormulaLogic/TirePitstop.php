<?php
namespace App\Model\FormulaLogic;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use App\Model\Entity\FoCar;
use App\Model\Entity\FoDamage;
use App\Model\Entity\FoLog;
use App\Model\FormulaLogic\DiceLogic;

class TirePitstop {
    use LocatorAwareTrait;

    public function __construct(FoCar $foCar)
    {
        $this->foCar = $foCar;
    }

    public function isEnteringPits() {
        return $this->foCar->fo_position->team_pits == $this->foCar->team && $this->foCar->last_pit_lap != $this->foCar->lap;
    }

    public function fixCar() {
        $maxTireDamage = $this->getTableLocator()->get('FoLogs')->find('all')->
            where(['fo_car_id' => $this->foCar->id, 'type' => FoLog::TYPE_INITIAL])->
            contain(['FoDamages'  => function(Query $q) {
                return $q->where(['type' => FoDamage::TYPE_TIRES]);
            }])->
            first()->
            fo_damages[0];

        $tireDamage = $this->foCar->getDamageByType(FoDamage::TYPE_TIRES);
        $tireDamage->wear_points = $maxTireDamage->wear_points;

        $tireDamage->save();

        (new FoLog([
            'fo_car_id' => $this->foCar->id,
            'type' => FoLog::TYPE_REPAIR,
            'fo_damages' => [$tireDamage->getDamageCopy()],
        ]))->save();

        $this->foCar->last_pit_lap = $this->foCar->lap;
        $this->foCar->pits_state = FoCar::PITS_STATE_IN_PITS;
        $this->foCar->save();
    }

    public function finishPitstop(callable $moveCar) {
        if ($this->foCar->pits_state == FoCar::PITS_STATE_LONG_STOP) {
            $this->foCar->pits_state = null;
            $this->foCar->save();
            return;
        }

        $pitstopRoll = DiceLogic::getDiceLogic()->getRoll(0);
        FoLog::logRoll($this->foCar, $pitstopRoll, FoLog::TYPE_LEAVING_PITS);
        if ($pitstopRoll < DiceLogic::BLACK_LONG_PITSTOP_BOTTOM) {
            $pitstopExitLength = $this->foCar->formula_game->fo_game->fo_track->pitlane_exit_length +
                ($this->foCar->team - 1) * 2; //drivers in the pits in the back can go further
            $movesLeft = min($pitstopExitLength, (int)ceil($pitstopRoll / 2));
            $this->foCar->gear = 4;

            FoLog::logRoll($this->foCar, $movesLeft, FoLog::TYPE_MOVE);

            $this->foCar->pits_state = null;

            $this->foCar->save();

            $moveCar($this->foCar, $movesLeft);
        } else {
            //long stop
            $this->foCar->gear = 3;
            $this->foCar->order = null;
            $this->foCar->pits_state = FoCar::PITS_STATE_LONG_STOP;
            $this->foCar->save();
        }

    }
}
