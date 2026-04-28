<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;

class DestroyUserController extends Controller
{
    public function __invoke($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $this->authorize('delete', $user);

        $user->forceDelete();

        session()->flash('success', __('Goodbye user! 😥'));

        return redirect()->action(IndexUserController::class);
    }
}
