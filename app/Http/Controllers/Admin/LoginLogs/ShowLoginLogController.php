<?php

namespace App\Http\Controllers\Admin\LoginLogs;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use Jenssegers\Agent\Agent;

class ShowLoginLogController extends Controller
{
    public function __invoke(LoginLog $loginLog)
    {
        $this->authorize('view', $loginLog);

        $agent = tap(new Agent, function ($agent) use ($loginLog) {
            $agent->setUserAgent($loginLog->user_agent);
        });

        return view('admin.loginLogs.show', [
            'loginLog' => $loginLog,
            'agent' => $agent
        ]);
    }
}
