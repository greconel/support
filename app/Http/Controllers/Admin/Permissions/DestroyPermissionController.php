<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class DestroyPermissionController extends Controller
{
    public function __invoke(Permission $permission)
    {
        $this->authorize('delete', $permission);

        $permission->delete();

        session()->flash('success', __('Goodbye permission! 😥'));

        return redirect()->action(IndexPermissionController::class);
    }
}
