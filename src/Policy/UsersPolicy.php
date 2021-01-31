<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Users;
use Authorization\IdentityInterface;

/**
 * Users policy
 */
class UsersPolicy
{
    /**
     * Check if $user can add Users
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Users $users
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Users $users)
    {
    }

    /**
     * Check if $user can edit Users
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Users $users
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Users $users)
    {
    }

    /**
     * Check if $user can delete Users
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Users $users
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Users $users)
    {
    }

    /**
     * Check if $user can view Users
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Users $users
     * @return bool
     */
    public function canView(IdentityInterface $user, Users $users)
    {
    }
}
