<?php

namespace App\Http\Controllers\Ampp\Implementations;

use App\Http\Controllers\Controller;
use App\Models\Implementation;

class ShowImplementationController extends Controller
{
    public function __invoke(Implementation $implementation)
    {
        $this->authorize('view', $implementation);

        $implementation->load([
            'latestSnapshot',
            'beaconKey',
            'schedules' => fn ($q) => $q->active()->with('latestExecution'),
        ]);

        // Heartbeat data for chart (last 24h, grouped by hour)
        $heartbeats = $implementation->heartbeats()
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at')
            ->get();

        // Recent errors
        $recentErrors = $implementation->errors()
            ->latest('occurred_at')
            ->take(20)
            ->get();

        $uptimePercentage = $implementation->uptime_percentage;

        return view('ampp.implementations.show', compact(
            'implementation',
            'heartbeats',
            'recentErrors',
            'uptimePercentage',
        ));
    }
}
