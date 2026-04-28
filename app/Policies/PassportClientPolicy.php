<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PassportClientPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view passport clients');
    }

    public function create(User $user): bool
    {
        return $user->can('create passport clients');
    }

    public function update(User $user): bool
    {
        return $user->can('edit passport clients');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete passport clients');
    }
}
