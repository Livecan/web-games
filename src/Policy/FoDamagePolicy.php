<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\FoDamage;
use Authorization\IdentityInterface;

/**
 * FoDamage policy
 */
class FoDamagePolicy
{
    /**
     * Check if $user can add FoDamage
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\FoDamage $foDamage
     * @return bool
     */
    public function canEditDamage(IdentityInterface $user, FoDamage $foDamage)
    {
        return $foDamage->fo_car->user_id === $user->id;
    }
}
