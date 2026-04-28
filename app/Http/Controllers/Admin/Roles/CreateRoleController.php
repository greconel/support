<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRoleController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Role::class);

        $permissions = Permission::all();

        return view('admin.roles.create', [
            'permissions' => $permissions
        ]);
    }
}
