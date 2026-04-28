<?php

namespace App\Policies;

use App\Models\ClientContact;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientContactPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view client contacts');
    }

    public function view(User $user, ClientContact $clientContact): bool
    {
        return $user->can('view client contacts');
    }

    public function create(User $user): bool
    {
        return $user->can('create client contacts');
    }

    public function update(User $user, ClientContact $clientContact): bool
    {
        return $user->can('edit client contacts');
    }

    public function delete(User $user, ClientContact $clientContact): bool
    {
        return $user->can('delete client contacts');
    }
}
