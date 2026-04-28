<?php

namespace App\Http\Controllers\Api\Beacon;

use App\Enums\ScheduleExecutionStatus;
use App\Http\Controllers\Controller;
use App\Models\ImplementationSchedule;
use App\Models\ScheduleExecution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PingController extends Controller
{
    public function store(string $token, Request $request): JsonResponse
    {
        $schedule = ImplementationSchedule::where('ping_token', $token)->first();

        if (!$schedule) {
            return response()->json(['error' => 'Invalid ping token'], 404);
        }

        $now = now();

        $schedule->update([
            'last_started_at' => $now,
            'last_finished_at' => $now,
            'last_exit_code' => $request->input('exit_code', 0),
        ]);

        $exitCode = $request->input('exit_code', 0);

        ScheduleExecution::create([
            'implementation_schedule_id' => $schedule->id,
            'started_at' => $now,
            'finished_at' => $now,
            'exit_code' => $exitCode,
            'output' => $request->input('output'),
            'status' => $exitCode === 0
                ? ScheduleExecutionStatus::Completed
                : ScheduleExecutionStatus::Failed,
        ]);

        return response()->json(['message' => 'ok']);
    }
}
