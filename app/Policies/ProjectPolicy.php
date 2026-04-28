<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view projects');
    }

    public function view(User $user, Project $project): bool
    {
        return $user->can('view projects');
    }

    public function create(User $user): bool
    {
        return $user->can('create projects');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->can('edit projects');
    }

    public function archive(User $user, Project $project): bool
    {
        return $user->can('delete projects');
    }

    public function restore(User $user, Project $project): bool
    {
        return $user->can('delete projects');
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can('delete projects');
    }

    public function files(User $user, Project $project): bool
    {
        return $user->can('manage files for projects');
    }

    public function reports(User $user): bool
    {
        return $user->can('view project reports');
    }

    public function emails(User $user, Project $project): bool
    {
        return $user->can('manage emails for projects');
    }
}
