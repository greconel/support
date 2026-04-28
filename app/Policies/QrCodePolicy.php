<?php

namespace App\Policies;

use App\Models\QrCode;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QrCodePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view qr codes');
    }

    public function view(User $user, QrCode $qrCode): bool
    {
        return $user->can('view qr codes');
    }

    public function create(User $user): bool
    {
        return $user->can('create qr codes');
    }

    public function update(User $user, QrCode $qrCode): bool
    {
        return $user->can('edit qr codes');
    }

    public function delete(User $user, QrCode $qrCode): bool
    {
        return $user->can('delete qr codes');
    }
}
