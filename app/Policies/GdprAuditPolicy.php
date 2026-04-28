<?php

namespace App\Policies;

use App\Models\GdprAudit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GdprAuditPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view gdpr audits');
    }

    public function view(User $user, GdprAudit $gdprAudit): bool
    {
        return $user->can('view gdpr audits');
    }

    public function create(User $user): bool
    {
        return $user->can('create gdpr audits');
    }

    public function update(User $user, GdprAudit $gdprAudit): bool
    {
        return $user->can('edit gdpr audits');
    }

    public function delete(User $user, GdprAudit $gdprAudit): bool
    {
        return $user->can('delete gdpr audits');
    }
}
