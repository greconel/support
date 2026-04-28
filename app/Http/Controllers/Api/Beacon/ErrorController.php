<?php

namespace App\Http\Controllers\Api\Beacon;

use App\Http\Controllers\Controller;
use App\Models\Implementation;
use App\Models\ImplementationError;
use App\Models\User;
use App\Notifications\Beacon\ImplementationErrorNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ErrorController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'level' => 'required|string|in:error,critical,emergency',
            'message' => 'required|string|max:1000',
            'exception_class' => 'nullable|string|max:255',
            'file' => 'nullable|string|max:255',
            'line' => 'nullable|integer',
            'trace' => 'nullable|string',
            'context' => 'nullable|array',
            'occurred_at' => 'required|date',
        ]);

        /** @var Implementation $implementation */
        $implementation = $request->get('implementation');

        if (!$implementation) {
            return response()->json(['error' => 'Key not registered to an implementation'], 403);
        }

        $error = ImplementationError::create([
            'implementation_id' => $implementation->id,
            'level' => $request->input('level'),
            'message' => $request->input('message'),
            'exception_class' => $request->input('exception_class'),
            'file' => $request->input('file'),
            'line' => $request->input('line'),
            'trace' => $request->input('trace'),
            'context' => $request->input('context'),
            'occurred_at' => $request->input('occurred_at'),
        ]);

        // Notify on critical and emergency errors
        if (in_array($request->input('level'), ['critical', 'emergency'])) {
            $admins = User::whereRelation('roles', 'name', '=', 'super admin')->get();
            Notification::send($admins, new ImplementationErrorNotification($implementation, $error));
        }

        return response()->json(['message' => 'ok'], 201);
    }
}
