<?php

namespace App\Policies;

use App\Models\HelpMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HelpMessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if ($user->can('view all help messages')){
            return true;
        }

        return $user->can('view all help messages');
    }

    public function view(User $user, HelpMessage $helpMessage): bool
    {
        if ($user->can('view all help messages')){
            return true;
        }

        if (! $user->can('view my help messages')){
            return false;
        }

        if ($helpMessage->user_id === $user->id){
            return true;
        }

        return false;
    }

    public function files(User $user, HelpMessage $helpMessage)
    {
        return $user->can('manage files for help messages');
    }
}
