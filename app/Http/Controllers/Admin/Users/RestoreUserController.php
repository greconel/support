<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;

class RestoreUserController extends Controller
{
    public function __invoke($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $this->authorize('restore', $user);

        $user->restore();

        session()->flash('success', __('User restored'));

        return redirect()->back();
    }
}
