<?php

namespace App\Http\Middleware;

use App\Enums\BeaconKeyStatus;
use App\Models\BeaconKey;
use Closure;
use Illuminate\Http\Request;

class VerifyBeaconKey
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Missing API key'], 401);
        }

        $beaconKey = BeaconKey::where('key', $token)->first();

        if (!$beaconKey) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        if ($beaconKey->status === BeaconKeyStatus::Disabled) {
            return response()->json(['error' => 'API key has been disabled'], 403);
        }

        $request->merge(['beacon_key' => $beaconKey]);

        if ($beaconKey->implementation) {
            $request->merge(['implementation' => $beaconKey->implementation]);
        }

        return $next($request);
    }
}
