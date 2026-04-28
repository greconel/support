<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;

class ShowUserController extends Controller
{
    public function __invoke(int $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $this->authorize('view', $user);

        $activityLogs = $user->actions()->orderByDesc('created_at')->paginate(5);

        return view('admin.users.show', [
            'user' => $user,
            'activityLogs' => $activityLogs
        ]);
    }
}
