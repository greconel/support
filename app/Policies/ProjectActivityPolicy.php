<?php

namespace App\Policies;

use App\Models\ProjectActivity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectActivityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view project activities');
    }

    public function create(User $user): bool
    {
        return $user->can('create project activities');
    }

    public function update(User $user, ProjectActivity $projectActivity): bool
    {
        return $user->can('edit project activities');
    }

    public function delete(User $user, ProjectActivity $projectActivity): bool
    {
        return $user->can('delete project activities');
    }
}
