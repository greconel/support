<?php

namespace App\Http\Controllers\Api\Beacon;

use App\Http\Controllers\Controller;
use App\Models\Implementation;
use App\Models\ImplementationSnapshot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PulseController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        /** @var Implementation $implementation */
        $implementation = $request->get('implementation');

        if (!$implementation) {
            return response()->json(['error' => 'Key not registered to an implementation'], 403);
        }

        // Accept both formats:
        // 1. { "system_info": { "php": {...}, ... }, "schedules": [...] }
        // 2. { "php": {...}, "laravel": {...}, "schedules": [...] }  (collectors at root level)
        if ($request->has('system_info')) {
            $systemInfo = $request->input('system_info');
        } else {
            $systemInfo = $request->except(['schedules', 'beacon_key', 'implementation']);
        }

        if (empty($systemInfo)) {
            return response()->json(['error' => 'No system info provided'], 422);
        }

        $updateData = ['last_push_at' => now()];

        if ($request->filled('app_name')) {
            $updateData['name'] = $request->input('app_name');
        }

        if ($request->filled('app_url')) {
            $updateData['app_url'] = $request->input('app_url');
        }

        if ($request->filled('heartbeat_token')) {
            $updateData['heartbeat_token'] = $request->input('heartbeat_token');
        }

        if ($request->filled('heartbeat_path')) {
            $updateData['heartbeat_path'] = $request->input('heartbeat_path');
        }

        $implementation->update($updateData);

        ImplementationSnapshot::create([
            'implementation_id' => $implementation->id,
            'data' => $systemInfo,
        ]);

        // Schedules can be at root level or nested inside system_info
        $schedules = $request->input('schedules', $systemInfo['schedules'] ?? null);

        if ($schedules) {
            $implementation->schedules()->update(['is_active' => false]);

            foreach ($schedules as $schedule) {
                $implementation->schedules()->updateOrCreate(
                    [
                        'command' => $schedule['command'],
                        'expression' => $schedule['expression'],
                    ],
                    [
                        'description' => $schedule['description'] ?? null,
                        'timezone' => $schedule['timezone'] ?? 'UTC',
                        'is_active' => true,
                    ]
                );
            }
        }

        return response()->json(['message' => 'ok']);
    }
}
