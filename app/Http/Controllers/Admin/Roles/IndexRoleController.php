<?php

namespace App\Http\Controllers\Admin\Roles;

use App\DataTables\Admin\RoleDataTable;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class IndexRoleController extends Controller
{
    public function __invoke(RoleDataTable $dataTable)
    {
        $this->authorize('viewAny', Role::class);

        return $dataTable->render('admin.roles.index');
    }
}
