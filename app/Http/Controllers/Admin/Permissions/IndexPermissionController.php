<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\DataTables\Admin\PermissionDataTable;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class IndexPermissionController extends Controller
{
    public function __invoke(PermissionDataTable $dataTable)
    {
        $this->authorize('viewAny', Permission::class);

        return $dataTable->render('admin.permissions.index');
    }
}
