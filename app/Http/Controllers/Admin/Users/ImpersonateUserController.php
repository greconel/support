<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;

class ImpersonateUserController extends Controller
{
    public function __invoke($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $this->authorize('impersonate', $user);

        auth()->login($user);

        return redirect('/');
    }
}
