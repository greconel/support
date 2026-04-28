<?php

namespace App\Http\Controllers\Api\Beacon;

use App\Enums\BeaconKeyStatus;
use App\Enums\ImplementationStatus;
use App\Enums\ImplementationType;
use App\Http\Controllers\Controller;
use App\Models\BeaconKey;
use App\Models\Implementation;
use App\Models\ImplementationSchedule;
use App\Models\ImplementationSnapshot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function verify(Request $request): JsonResponse
    {
        $key = $request->query('key');

        if (!$key) {
            return response()->json(['error' => 'Missing key parameter'], 400);
        }

        $beaconKey = BeaconKey::where('key', $key)->first();

        if (!$beaconKey) {
            return response()->json(['error' => 'Invalid key'], 404);
        }

        return response()->json([
            'status' => $beaconKey->status->value,
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'app_url' => 'required|url',
            'app_name' => 'required|string|max:255',
            'heartbeat_token' => 'required|string|max:128',
            'heartbeat_path' => 'nullable|string|max:255',
            'system_info' => 'nullable|array',
            'schedules' => 'nullable|array',
            'schedules.*.command' => 'required_with:schedules|string',
            'schedules.*.expression' => 'required_with:schedules|string',
            'schedules.*.description' => 'nullable|string',
            'schedules.*.timezone' => 'nullable|string',
        ]);

        /** @var BeaconKey $beaconKey */
        $beaconKey = $request->get('beacon_key');

        // If already claimed, check if this is a re-registration from the same app
        if ($beaconKey->status === BeaconKeyStatus::Claimed) {
            $existing = $beaconKey->implementation;

            if ($existing && $existing->app_url === $request->input('app_url')) {
                // Re-registration from same app — update
                return $this->updateExisting($existing, $request);
            }

            return response()->json([
                'error' => 'This key is already bound to another project.',
            ], 409);
        }

        // Create new implementation
        $implementation = Implementation::create([
            'name' => $request->input('app_name'),
            'app_url' => $request->input('app_url'),
            'type' => ImplementationType::Beacon,
            'status' => ImplementationStatus::Unknown,
            'heartbeat_token' => $request->input('heartbeat_token'),
            'heartbeat_path' => $request->input('heartbeat_path', '/beacon/heartbeat'),
        ]);

        $beaconKey->claim($implementation);

        // Store initial snapshot
        if ($request->has('system_info')) {
            ImplementationSnapshot::create([
                'implementation_id' => $implementation->id,
                'data' => $request->input('system_info'),
            ]);
        }

        // Register schedules
        $this->syncSchedules($implementation, $request->input('schedules', []));

        return response()->json([
            'implementation_id' => $implementation->id,
            'message' => 'Registered successfully',
        ], 201);
    }

    private function updateExisting(Implementation $implementation, Request $request): JsonResponse
    {
        $implementation->update([
            'name' => $request->input('app_name'),
            'heartbeat_token' => $request->input('heartbeat_token'),
            'heartbeat_path' => $request->input('heartbeat_path', '/beacon/heartbeat'),
        ]);

        if ($request->has('system_info')) {
            ImplementationSnapshot::create([
                'implementation_id' => $implementation->id,
                'data' => $request->input('system_info'),
            ]);
        }

        $this->syncSchedules($implementation, $request->input('schedules', []));

        return response()->json([
            'implementation_id' => $implementation->id,
            'message' => 'Re-registered successfully',
        ], 200);
    }

    private function syncSchedules(Implementation $implementation, array $schedules): void
    {
        // Deactivate all existing schedules
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
}
