<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\UsersTable;
use Authorization\IdentityInterface;
use Authorization\Policy\BeforePolicyInterface;

/**
 * Users policy
 */
class UsersTablePolicy implements BeforePolicyInterface
{
    public function before($user, $resource, $action)
    {
        if ($user->getOriginalData()->is_admin) {
            return true;
        }
        // fall through
    }
    
    public function canIndex(IdentityInterface $user, $users)
    {
        if ($user->getOriginalData()->is_admin) {
            return true;
        } else {
            return false;
        }
    }
}
