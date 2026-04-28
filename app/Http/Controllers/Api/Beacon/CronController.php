<?php

namespace App\Http\Controllers\Api\Beacon;

use App\Enums\ScheduleExecutionStatus;
use App\Http\Controllers\Controller;
use App\Models\Implementation;
use App\Models\ScheduleExecution;
use App\Models\User;
use App\Notifications\Beacon\ScheduleFailedNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CronController extends Controller
{
    public function started(Request $request): JsonResponse
    {
        $request->validate([
            'command' => 'required|string',
            'expression' => 'required|string',
            'started_at' => 'required|date',
        ]);

        /** @var Implementation $implementation */
        $implementation = $request->get('implementation');

        if (!$implementation) {
            return response()->json(['error' => 'Key not registered to an implementation'], 403);
        }

        $schedule = $implementation->schedules()
            ->where('command', $request->input('command'))
            ->where('expression', $request->input('expression'))
            ->first();

        // Auto-register unknown schedules
        if (!$schedule) {
            $schedule = $implementation->schedules()->create([
                'command' => $request->input('command'),
                'expression' => $request->input('expression'),
                'is_active' => true,
            ]);
        }

        $schedule->update(['last_started_at' => $request->input('started_at')]);

        ScheduleExecution::create([
            'implementation_schedule_id' => $schedule->id,
            'started_at' => $request->input('started_at'),
            'status' => ScheduleExecutionStatus::Started,
        ]);

        return response()->json(['message' => 'ok']);
    }

    public function finished(Request $request): JsonResponse
    {
        $request->validate([
            'command' => 'required|string',
            'expression' => 'required|string',
            'exit_code' => 'required|integer',
            'finished_at' => 'required|date',
            'output' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        /** @var Implementation $implementation */
        $implementation = $request->get('implementation');

        if (!$implementation) {
            return response()->json(['error' => 'Key not registered to an implementation'], 403);
        }

        $schedule = $implementation->schedules()
            ->where('command', $request->input('command'))
            ->where('expression', $request->input('expression'))
            ->first();

        if (!$schedule) {
            return response()->json(['error' => 'Schedule not found'], 404);
        }

        $schedule->update([
            'last_finished_at' => $request->input('finished_at'),
            'last_exit_code' => $request->input('exit_code'),
        ]);

        // Find the latest "started" execution for this schedule and update it
        $execution = $schedule->executions()
            ->where('status', ScheduleExecutionStatus::Started)
            ->latest('started_at')
            ->first();

        $exitCode = $request->input('exit_code');
        $status = $request->input('status') === 'failed' || $exitCode !== 0
            ? ScheduleExecutionStatus::Failed
            : ScheduleExecutionStatus::Completed;

        if ($execution) {
            $finishedAt = $request->input('finished_at');
            $durationMs = $execution->started_at->diffInMilliseconds($finishedAt);

            $execution->update([
                'finished_at' => $finishedAt,
                'duration_ms' => $durationMs,
                'exit_code' => $exitCode,
                'output' => $request->input('output'),
                'status' => $status,
            ]);
        } else {
            // No matching start found, create a standalone finish record
            ScheduleExecution::create([
                'implementation_schedule_id' => $schedule->id,
                'started_at' => $request->input('finished_at'),
                'finished_at' => $request->input('finished_at'),
                'exit_code' => $exitCode,
                'output' => $request->input('output'),
                'status' => $status,
            ]);
        }

        // Notify on failure
        if ($status === ScheduleExecutionStatus::Failed) {
            $finalExecution = $execution ?? ScheduleExecution::where('implementation_schedule_id', $schedule->id)
                ->latest('created_at')
                ->first();

            if ($finalExecution) {
                $admins = User::whereRelation('roles', 'name', '=', 'super admin')->get();
                Notification::send($admins, new ScheduleFailedNotification($schedule, $finalExecution));
            }
        }

        return response()->json(['message' => 'ok']);
    }
}
