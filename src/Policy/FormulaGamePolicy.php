<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\FormulaGame;
use App\Model\Entity\FoCar;
use Authorization\IdentityInterface;

/**
 * FormulaGame policy
 */
class FormulaGamePolicy
{
    /**
     * Check if $user can start the Formula game
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Game $game
     * @return bool
     */
    public function canStart(IdentityInterface $user, FormulaGame $formulaGame)
    {
        return $user->id == $formulaGame->creator_id || $user->is_admin;
    }

    /**
     * Check if $user can retrieve board info
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Game $game
     * @return bool
     */
    public function canGetBoardUpdateJson(IdentityInterface $user, FormulaGame $formulaGame)
    {
        return $this->isGamePlayer($user, $formulaGame);
    }

    /**
     * Check if $user can retrieve board
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Game $game
     * @return bool
     */
    public function canGetBoard(IdentityInterface $user, FormulaGame $formulaGame)
    {
        return $this->isGamePlayer($user, $formulaGame);
    }

    public function canChooseMoveOption(IdentityInterface $user, FormulaGame $formulaGame)
    {
        return $user->id == collection($formulaGame->fo_cars)->
                filter(function(FoCar $foCar) { return $foCar->order != null; })->
                sortBy('order', SORT_ASC)->
                first()->user_id;
    }

    public function canChooseGear(IdentityInterface $user, FormulaGame $formulaGame)
    {
        return $user->id == collection($formulaGame->fo_cars)->
                filter(function(FoCar $foCar) { return $foCar->order != null; })->
                sortBy('order', SORT_ASC)->
                first()->user_id;
    }

    public function canChoosePitsOptions(IdentityInterface $user, FormulaGame $formulaGame)
    {
        return $user->id == collection($formulaGame->fo_cars)->
                filter(function(FoCar $foCar) { return $foCar->order != null; })->
                sortBy('order', SORT_ASC)->
                first()->user_id;
    }

    public function canGetWaitingRoom(IdentityInterface $user, FormulaGame $formulaGame)
    {
        return $this->isGamePlayer($user, $formulaGame);
    }

    public function canGetSetupUpdateJson(IdentityInterface $user, FormulaGame $formulaGame)
    {
        return $this->isGamePlayer($user, $formulaGame);
    }

    public function canEditSetup(IdentityInterface $user, FormulaGame $formulaGame) {
        return $user->id == $formulaGame->creator_id || $user->is_admin;
    }

    public function canSetUserReady(IdentityInterface $user, FormulaGame $formulaGame) {
        return $this->isGamePlayer($user, $formulaGame);
    }

    private function isGamePlayer(IdentityInterface $user, FormulaGame $formulaGame)
    {
        return collection($formulaGame->users)->some(
                function($player) use ($user) { return $player->id == $user->id; })
                || $user->is_admin;
    }
}
