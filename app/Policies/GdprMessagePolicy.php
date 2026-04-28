<?php

namespace App\Policies;

use App\Models\GdprMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GdprMessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view gdpr messages');
    }

    public function view(User $user, GdprMessage $gdprMessage): bool
    {
        return $user->can('view gdpr messages');
    }

    public function create(User $user): bool
    {
        return $user->can('create gdpr messages');
    }

    public function update(User $user, GdprMessage $gdprMessage): bool
    {
        return $user->can('edit gdpr messages');
    }

    public function delete(User $user, GdprMessage $gdprMessage): bool
    {
        return $user->can('delete gdpr messages');
    }
}
