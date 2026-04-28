<?php

namespace App\Http\Controllers\Ampp\Implementations;

use App\Enums\ImplementationType;
use App\Http\Controllers\Controller;
use App\Models\Implementation;
use Illuminate\Http\Request;

class StoreImplementationController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('create', Implementation::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'app_url' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'heartbeat_interval' => 'nullable|integer|min:10|max:3600',
        ]);

        $implementation = Implementation::create([
            'name' => $validated['name'],
            'app_url' => $validated['app_url'] ?? null,
            'type' => ImplementationType::Manual,
            'notes' => $validated['notes'] ?? null,
            'tags' => !empty($validated['tags'])
                ? array_map('trim', explode(',', $validated['tags']))
                : null,
            'heartbeat_interval' => $validated['heartbeat_interval'] ?? 60,
        ]);

        session()->flash('success', __('Implementation created successfully'));

        return redirect()->action(ShowImplementationController::class, $implementation);
    }
}
