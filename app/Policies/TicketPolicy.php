<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view tickets');
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $user->can('view tickets');
    }

    public function create(User $user): bool
    {
        return $user->can('create tickets');
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->can('edit tickets');
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->can('delete tickets');
    }

    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->can('assign tickets');
    }

    public function close(User $user, Ticket $ticket): bool
    {
        return $user->can('close tickets');
    }

    public function reply(User $user, Ticket $ticket): bool
    {
        return $user->can('edit tickets');
    }
}
