<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\StoreRoleRequest;
use Spatie\Permission\Models\Role;

class StoreRoleController extends Controller
{
    public function __invoke(StoreRoleRequest $request)
    {
        $this->authorize('create', Role::class);

        $role = Role::create(['name' => $request->input('name')]);

        $role->givePermissionTo($request->input('permissions'));

        session()->flash('success', __('New role created'));

        return redirect()->action(IndexRoleController::class);
    }
}
