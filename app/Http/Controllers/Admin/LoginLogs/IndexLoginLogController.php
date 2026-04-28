<?php

namespace App\Http\Controllers\Admin\LoginLogs;

use App\DataTables\Admin\LoginLogDataTable;
use App\Http\Controllers\Controller;
use App\Models\LoginLog;

class IndexLoginLogController extends Controller
{
    public function __invoke(LoginLogDataTable $dataTable)
    {
        $this->authorize('viewAny', LoginLog::class);

        return $dataTable->render('admin.loginLogs.index');
    }
}
