<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class EditPermissionController extends Controller
{
    public function __invoke(Permission $permission)
    {
        $this->authorize('update', $permission);

        return view('admin.permissions.edit', [
            'permission' => $permission
        ]);
    }
}
