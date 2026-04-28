<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;

class EditUserController extends Controller
{
    public function __invoke($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $this->authorize('update', $user);

        $roles = Role::whereNotIn('name', ['super admin'])
            ->get()
            ->pluck('name', 'id')
            ->toArray()
        ;

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }
}
