<?php

namespace App\Http\Controllers\Admin\Users;

use App\DataTables\Admin\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class IndexUserController extends Controller
{
    public function __invoke(UserDataTable $dataTable, Request $request)
    {
        $this->authorize('viewAny', User::class);

        return $dataTable
            ->with([
                'role' => $request->get('role'),
                'archive' => $request->get('archive')
            ])
            ->render('admin.users.index', [
                'adminCount' => User::query()->whereRelation('roles', 'name', '=', 'ampp user')->count(),
                'clientCount' => User::query()->whereRelation('roles', 'name', '=', 'client')->count(),
                'archiveCount' => User::query()->onlyTrashed()->count(),
            ])
        ;
    }
}
