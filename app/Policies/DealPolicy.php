<?php

namespace App\Policies;

use App\Models\Deal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DealPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view deals');
    }

    public function view(User $user, Deal $deal): bool
    {
        return $user->can('view deals');
    }

    public function create(User $user): bool
    {
        return $user->can('create deals');
    }

    public function update(User $user, Deal $deal): bool
    {
        return $user->can('edit deals');
    }

    public function delete(User $user, Deal $deal): bool
    {
        return $user->can('delete deals');
    }

    public function files(User $user, Deal $deal): bool
    {
        return $user->can('manage files for deals');
    }
}
