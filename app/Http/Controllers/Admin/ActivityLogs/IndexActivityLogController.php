<?php

namespace App\Http\Controllers\Admin\ActivityLogs;

use App\DataTables\Admin\ActivityDataTable;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class IndexActivityLogController extends Controller
{
    public function __invoke(ActivityDataTable $dataTable)
    {
        $this->authorize('viewAny', Activity::class);

        return $dataTable->render('admin.activityLogs.index');
    }
}
