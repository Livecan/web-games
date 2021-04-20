<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\FoTracksTable;
use Authorization\IdentityInterface;

/**
 * FoTracksTable policy
 */
class FoTracksTablePolicy
{
    /**
     * Check if $user can index FoTracksTable
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\FoTracksTable $foTracksTable
     * @return bool
     */
    public function canIndex(IdentityInterface $user, FoTracksTable $foTracksTable)
    {
        return $user->is_admin;
    }
}
