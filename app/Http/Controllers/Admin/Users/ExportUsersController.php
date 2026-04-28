<?php

namespace App\Http\Controllers\Admin\Users;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\User;

class ExportUsersController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', User::class);

        return (new UserExport)->download('users.xlsx');
    }
}
