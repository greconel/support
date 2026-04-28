<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view quotations');
    }

    public function view(User $user, Quotation $quotation): bool
    {
        return $user->can('view quotations');
    }

    public function create(User $user): bool
    {
        return $user->can('create quotations');
    }

    public function update(User $user, Quotation $quotation): bool
    {
        return $user->can('edit quotations');
    }

    public function delete(User $user, Quotation $quotation): bool
    {
        return $user->can('delete quotations');
    }

    public function files(User $user, Quotation $quotation): bool
    {
        return $user->can('manage files for quotations');
    }
}
