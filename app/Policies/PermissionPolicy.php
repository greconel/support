<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view permissions');
    }

    public function create(User $user): bool
    {
        return $user->can('create permissions');
    }

    public function update(User $user): bool
    {
        return $user->can('edit permissions');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete permissions');
    }
}
