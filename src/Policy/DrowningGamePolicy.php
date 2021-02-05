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
        return $drowningGame->users[0]->id == $user->id or $user->getOriginalData()->is_admin;
    }
}
