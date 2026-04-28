<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permissions\UpdatePermissionRequest;
use Spatie\Permission\Models\Permission;

class UpdatePermissionController extends Controller
{
    public function __invoke(UpdatePermissionRequest $request, Permission $permission)
    {
        $this->authorize('update', $permission);

        $permission->update(['name' => $request->input('name')]);

        session()->flash('success', __('Permission edited'));

        return redirect()->action(IndexPermissionController::class);
    }
}
