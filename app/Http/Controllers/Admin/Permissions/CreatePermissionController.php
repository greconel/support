<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class CreatePermissionController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Permission::class);

        return view('admin.permissions.create');
    }
}
