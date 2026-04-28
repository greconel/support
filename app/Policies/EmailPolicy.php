<?php

namespace App\Policies;

use App\Models\Email;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmailPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Email $email): bool
    {
        $model = $email->model_type::find($email->model_id);

        if (! $model){
            return false;
        }

        return $user->can('view', $model);
    }

    public function files(User $user, Email $email): bool
    {
        $model = $email->model_type::find($email->model_id);

        if (! $model){
            return false;
        }

        return $user->can('files', $model);
    }
}
