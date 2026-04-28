<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class DestroyRoleController extends Controller
{
    public function __invoke(Role $role)
    {
        $this->authorize('delete', Role::class);

        $role->delete();

        session()->flash('success', __('Goodbye role! 😥'));

        return redirect()->action(IndexRoleController::class);
    }
}
