<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Roles\UpdateRoleRequest;
use Spatie\Permission\Models\Role;

class UpdateRoleController extends Controller
{
    public function __invoke(UpdateRoleRequest $request, Role $role)
    {
        $this->authorize('update', $role);

        $role->update(['name' => $request->input('name')]);

        $role->syncPermissions($request->input('permissions'));

        session()->flash('success', __('Updated role'));

        return redirect()->action(IndexRoleController::class);
    }
}
