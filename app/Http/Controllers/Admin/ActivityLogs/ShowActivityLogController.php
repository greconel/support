<?php

namespace App\Http\Controllers\Admin\ActivityLogs;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ShowActivityLogController extends Controller
{
    public function __invoke(Activity $activityLog)
    {
        $this->authorize('view', $activityLog);

        return view('admin.activityLogs.show', [
            'activityLog' => $activityLog
        ]);
    }
}
