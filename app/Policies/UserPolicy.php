<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view users');
    }

    public function view(User $user, User $model): bool
    {
        if ($model->hasRole('super admin')){
            return false;
        }

        if ($user->id == $model->id){
            return false;
        }

        return $user->can('view users');
    }

    public function create(User $user): bool
    {
        return $user->can('create users');
    }

    public function update(User $user, User $model): bool
    {
        if ($model->hasRole('super admin')){
            return false;
        }

        if ($user->id == $model->id){
            return false;
        }

        return $user->can('edit users');
    }

    public function archive(User $user, User $model): bool
    {
        if ($model->hasRole('super admin')){
            return false;
        }

        if ($user->id == $model->id){
            return false;
        }

        return $user->can('delete users');
    }

    public function restore(User $user, User $model): bool
    {
        if ($model->hasRole('super admin')){
            return false;
        }

        if ($user->id == $model->id){
            return false;
        }

        return $user->can('delete users');
    }

    public function delete(User $user, User $model): bool
    {
        if ($model->hasRole('super admin')){
            return false;
        }

        if ($user->id == $model->id){
            return false;
        }

        return $user->can('delete users');
    }

    public function impersonate(User $user, User $model): bool
    {
        if ($model->hasRole('super admin')){
            return false;
        }

        if ($user->id == $model->id){
            return false;
        }

        return $user->can('impersonate users');
    }

    public function files(User $user, User $model): bool
    {
        return true;
    }
}
