<?php

namespace App\Http\Controllers\Ampp\Implementations;

use App\Http\Controllers\Controller;
use App\Models\Implementation;
use Illuminate\Http\Request;

class UpdateImplementationController extends Controller
{
    public function __invoke(Request $request, Implementation $implementation)
    {
        $this->authorize('update', $implementation);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'app_url' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'heartbeat_interval' => 'nullable|integer|min:10|max:3600',
        ]);

        $implementation->update([
            'name' => $validated['name'],
            'app_url' => $validated['app_url'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'tags' => !empty($validated['tags'])
                ? array_map('trim', explode(',', $validated['tags']))
                : null,
            'heartbeat_interval' => $validated['heartbeat_interval'] ?? $implementation->heartbeat_interval,
        ]);

        session()->flash('success', __('Implementation updated successfully'));

        return redirect()->action(ShowImplementationController::class, $implementation);
    }
}
