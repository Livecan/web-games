<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\DrowningGame;
use Authorization\IdentityInterface;

/**
 * DrowningGame policy
 */
class DrowningGamePolicy
{
    /**
     * Check if $user can start a DrowningGame
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\DrowningGame $drowningGame
     * @return bool
     */
    public function canStart(IdentityInterface $user, DrowningGame $drowningGame)
    {
        return $drowningGame->users[0]->id == $user->id || $user->getOriginalData()->is_admin;
    }
    
    /**
     * Check if $user can open board of Drowning Game
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\DrowningGame $drowningGame
     * @return bool
     */
    public function canOpenBoard(IdentityInterface $user, DrowningGame $drowningGame)
    {
        return array_filter($drowningGame->users,
                function($_user) use ($user) { return $_user->id == $user->id; }) ||
                $user->getOriginalData()->is_admin;
    }
    
    /**
     * Check if $user can open board of Drowning Game
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\DrowningGame $drowningGame
     * @return bool
     */
    public function canOpenReloadBoard(IdentityInterface $user, DrowningGame $drowningGame)
    {
        return array_filter($drowningGame->users,
                function($_user) use ($user) { return $_user->id == $user->id; }) ||
                $user->getOriginalData()->is_admin;
    }
    
    /**
     * Check if $user can update board of Drowning Game
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\DrowningGame $drowningGame
     * @return bool
     */
    public function canUpdateBoardJson(IdentityInterface $user, DrowningGame $drowningGame)
    {
        return array_filter($drowningGame->users,
                function($_user) use ($user) { return $_user->id == $user->id; }) ||
                $user->getOriginalData()->is_admin;
    }
    
    public function canProcessActions(IdentityInterface $user, DrowningGame $drowningGame)
    {
        return $drowningGame->currentTurnPlayer->id == $user->id;
    }
}
