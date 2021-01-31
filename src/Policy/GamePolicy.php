<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Game;
use Authorization\IdentityInterface;

/**
 * Game policy
 */
class GamePolicy
{
    /**
     * Check if $user can add Game
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Game $game
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Game $game)
    {
        return true;
    }

    /**
     * Check if $user can edit Game
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Game $game
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Game $game)
    {
        return $this->isPlayer($user, $game) || $user->getOriginalData()->is_admin;
    }

    /**
     * Check if $user can delete Game
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Game $game
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Game $game)
    {
        return $this->isPlayer($user, $game) || $user->getOriginalData()->is_admin;
    }

    /**
     * Check if $user can view Game
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Game $game
     * @return bool
     */
    public function canView(IdentityInterface $user, Game $game)
    {
        return true;
    }
    
    protected function isPlayer(IdentityInterface $user, Game $game) {
        foreach ($game->users as $player) {
            if ($player->id == $user->getIdentifier()) {
                return true;
            }
        }
        return false;
    }
}
