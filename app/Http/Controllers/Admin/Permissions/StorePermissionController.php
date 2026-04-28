<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permissions\StorePermissionRequest;
use Spatie\Permission\Models\Permission;

class StorePermissionController extends Controller
{
    public function __invoke(StorePermissionRequest $request)
    {
        $this->authorize('update', Permission::class);

        Permission::create(['name' => $request->input('name')]);

        session()->flash('success', __('Created new permission'));

        return redirect()->action(IndexPermissionController::class);
    }
}
