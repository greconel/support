<?php

namespace App\Policies;

use App\Models\Release;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReleasePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('import releases');
    }

    public function update(User $user, Release $release): bool
    {
        return $user->can('update releases');
    }
}
