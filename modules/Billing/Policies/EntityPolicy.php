<?php

namespace Module\Billing\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntityPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string $ability
     *
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->can('billing.'.strtolower($ability))) {
            return true;
        }
    }
}