<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;

class ArchiveUserController extends Controller
{
    public function __invoke(User $user)
    {
        $this->authorize('archive', $user);

        $user->delete();

        session()->flash('success', __('User archived'));

        return redirect()->back();
    }
}
