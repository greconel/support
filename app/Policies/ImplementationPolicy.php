<?php

namespace App\Policies;

use App\Models\Implementation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImplementationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view implementations');
    }

    public function view(User $user, Implementation $implementation): bool
    {
        return $user->can('view implementations');
    }

    public function create(User $user): bool
    {
        return $user->can('manage implementations');
    }

    public function update(User $user, Implementation $implementation): bool
    {
        return $user->can('manage implementations');
    }

    public function delete(User $user, Implementation $implementation): bool
    {
        return $user->can('manage implementations');
    }
}
