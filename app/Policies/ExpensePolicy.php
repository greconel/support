<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view expenses');
    }

    public function view(User $user, Expense $expense): bool
    {
        return $user->can('view expenses');
    }

    public function create(User $user): bool
    {
        return $user->can('create expenses');
    }

    public function update(User $user, Expense $expense): bool
    {
        return $user->can('edit expenses');
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $user->can('delete expenses');
    }

    public function changeStatus(User $user, Expense $expense)
    {
        return $user->can('change status for expenses');
    }

    public function files(User $user, Expense $expense)
    {
        return $user->can('manage files for expenses');
    }
}
