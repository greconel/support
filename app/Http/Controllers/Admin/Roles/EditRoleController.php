<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditRoleController extends Controller
{
    public function __invoke(Role $role)
    {
        $this->authorize('update', $role);

        $permissions = Permission::all();

        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => $permissions
        ]);
    }
}
