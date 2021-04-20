<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\FoTrack;
use Authorization\IdentityInterface;

/**
 * FoTracks policy
 */
class FoTrackPolicy
{
    /**
     * Check if $user can view FoTracks
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\FoTracks $foTracks
     * @return bool
     */
    public function canView(IdentityInterface $user, FoTrack $foTracks)
    {
        return $user->is_admin;
    }
}
