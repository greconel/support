<?php

namespace App\Policies;

use App\Models\TimeRegistration;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimeRegistrationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return ($user->can('view time registrations') || $user->hasRole('PM'));
    }

    public function view(User $user, TimeRegistration $timeRegistration): bool
    {
        if($user->cannot('view time registrations')) {
            return false;
        }

        if($user->can('view other users time registrations')) {
            return true;
        }

        return $user->id === $timeRegistration->user_id;
    }

    public function viewOtherUsers(User $user): bool
    {
        return $user->can('view other users time registrations');
    }

    public function create(User $user): bool
    {
        return $user->can('create time registrations');
    }

    public function update(User $user, TimeRegistration $timeRegistration): bool
    {
        if($user->cannot('edit time registrations')) {
            return false;
        }

        if($user->can('view other users time registrations')) {
            return true;
        }

        return $user->id === $timeRegistration->user_id;
    }

    public function delete(User $user, TimeRegistration $timeRegistration): bool
    {
        if($user->cannot('delete time registrations')) {
            return false;
        }

        if($user->can('view other users time registrations')) {
            return true;
        }

        return $user->id === $timeRegistration->user_id;
    }
}
