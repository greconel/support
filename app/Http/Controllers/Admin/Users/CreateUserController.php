<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CreateUserController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', User::class);

        $roles = Role::whereNotIn('name', ['super admin'])
            ->get()
            ->pluck('name', 'id')
            ->toArray()
        ;

        return view('admin.users.create', [
            'roles' => $roles
        ]);
    }
}
