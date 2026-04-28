<?php

namespace App\Policies;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConnectionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view connections');
    }

    public function update(User $user, Connection $connection): bool
    {
        return $user->can('update connections');
    }

    public function create(User $user): bool
    {
        return $user->can('create connections');
    }
}
