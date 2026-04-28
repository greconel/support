<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/*
 * @hideFromAPIDocumentation
 */
class ConnectionsLogController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string']
        ]);

        $logName = $request->input('name') . '.log';

        if (!Storage::disk('logs')->exists($logName)){
            Storage::disk('logs')->put($logName, null);
        }

        Storage::disk('logs')->append($logName, $request->input('content') . '\n\n');
    }
}
