<?php

namespace App\Policies;

use App\Models\GdprChecklist;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GdprChecklistPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view gdpr checklists');
    }

    public function update(User $user, GdprChecklist $gdprChecklist): bool
    {
        return $user->can('edit gdpr checklists');
    }
}
