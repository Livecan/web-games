<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\FormulaGame;
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
        return $user->id == $formulaGame->formula_game->creator_id || $user->is_admin;
    }
}
