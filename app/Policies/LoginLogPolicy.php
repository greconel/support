<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LoginLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoginLogPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view login logs');
    }

    public function view(User $user, LoginLog $loginLog): bool
    {
        return $user->can('view login logs');
    }
}
