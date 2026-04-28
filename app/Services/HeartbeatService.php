<?php

namespace App\Services;

use App\Enums\ImplementationStatus;
use App\Enums\ImplementationType;
use App\Models\Implementation;
use App\Models\ImplementationHeartbeat;
use App\Models\User;
use App\Notifications\Beacon\ImplementationBackUpNotification;
use App\Notifications\Beacon\ImplementationDownNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class HeartbeatService
{
    public function pingAll(): void
    {
        Implementation::query()
            ->whereNotNull('app_url')
            ->whereNull('deleted_at')
            ->each(function (Implementation $implementation) {
                $this->ping($implementation);
            });
    }

    public function ping(Implementation $implementation): ImplementationHeartbeat
    {
        // Beacon projects: append heartbeat path + token
        // Manual projects: just ping the URL as-is
        if ($implementation->type === ImplementationType::Beacon) {
            $url = rtrim($implementation->app_url, '/') . $implementation->heartbeat_path;

            if ($implementation->heartbeat_token) {
                $url .= '?token=' . $implementation->heartbeat_token;
            }
        } else {
            $url = $implementation->app_url;
        }

        $startTime = microtime(true);
        $success = false;
        $statusCode = null;

        Log::debug('Heartbeat: pinging', ['url' => $url, 'implementation' => $implementation->name]);

        try {
            $response = Http::timeout(10)
                ->get($url);

            $statusCode = $response->status();
            $success = $response->successful();

            if (!$success) {
                Log::warning('Heartbeat: non-success response', [
                    'implementation' => $implementation->name,
                    'url' => $url,
                    'status' => $statusCode,
                    'body' => substr($response->body(), 0, 500),
                ]);
            }
        } catch (\Exception $e) {
            $success = false;
            Log::warning('Heartbeat: request failed', [
                'implementation' => $implementation->name,
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }

        $responseTimeMs = (int) round((microtime(true) - $startTime) * 1000);

        $heartbeat = ImplementationHeartbeat::create([
            'implementation_id' => $implementation->id,
            'status_code' => $statusCode,
            'response_time_ms' => $responseTimeMs,
            'success' => $success,
        ]);

        $previousStatus = $implementation->status;

        // Update implementation status
        $newStatus = $this->determineStatus($implementation);
        $updateData = ['status' => $newStatus];

        if ($success) {
            $updateData['last_heartbeat_at'] = now();
        }

        $implementation->update($updateData);

        // Notify on status transitions
        $this->handleStatusTransition($implementation, $previousStatus, $newStatus);

        return $heartbeat;
    }

    private function handleStatusTransition(
        Implementation $implementation,
        ImplementationStatus $previousStatus,
        ImplementationStatus $newStatus,
    ): void {
        $admins = User::whereRelation('roles', 'name', '=', 'super admin')->get();

        if ($admins->isEmpty()) {
            return;
        }

        // Went offline: notify after 2 consecutive failures (= ~2 min downtime)
        if ($newStatus === ImplementationStatus::Offline && $previousStatus !== ImplementationStatus::Offline) {
            $consecutiveFailures = $implementation->heartbeats()
                ->latest('created_at')
                ->take(10)
                ->get()
                ->takeWhile(fn ($hb) => !$hb->success)
                ->count();

            if ($consecutiveFailures >= 2) {
                Notification::send($admins, new ImplementationDownNotification($implementation, $consecutiveFailures));
                Log::info('Beacon: sent down notification', ['implementation' => $implementation->name]);
            }
        }

        // Came back online after being offline
        if ($newStatus === ImplementationStatus::Online && $previousStatus === ImplementationStatus::Offline) {
            Notification::send($admins, new ImplementationBackUpNotification($implementation));
            Log::info('Beacon: sent recovery notification', ['implementation' => $implementation->name]);
        }
    }

    private function determineStatus(Implementation $implementation): ImplementationStatus
    {
        $recentHeartbeats = $implementation->heartbeats()
            ->latest('created_at')
            ->take(5)
            ->get();

        if ($recentHeartbeats->isEmpty()) {
            return ImplementationStatus::Unknown;
        }

        $successCount = $recentHeartbeats->where('success', true)->count();
        $total = $recentHeartbeats->count();

        if ($successCount === $total) {
            return ImplementationStatus::Online;
        }

        if ($successCount === 0) {
            return ImplementationStatus::Offline;
        }

        return ImplementationStatus::Degraded;
    }
}
