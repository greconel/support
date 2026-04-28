<?php

namespace App\Policies;

use App\Models\GdprRegister;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GdprRegisterPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view gdpr registers');
    }

    public function view(User $user, GdprRegister $gdprRegister): bool
    {
        return $user->can('view gdpr registers');
    }

    public function create(User $user): bool
    {
        return $user->can('create gdpr registers');
    }

    public function update(User $user, GdprRegister $gdprRegister): bool
    {
        return $user->can('edit gdpr registers');
    }

    public function delete(User $user, GdprRegister $gdprRegister): bool
    {
        return $user->can('delete gdpr registers');
    }
}
