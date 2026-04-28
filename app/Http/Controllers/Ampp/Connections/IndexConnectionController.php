<?php

namespace App\Http\Controllers\Ampp\Connections;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class IndexConnectionController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Connection::class);

        $connections = Connection::orderByRaw("CASE id
                WHEN 1 THEN 0
                WHEN 2 THEN 0
                WHEN 3 THEN 0
                WHEN 4 THEN 0
                ELSE 1
    END,
    name")
            ->get();
        $connectionsContent = Str::markdown(File::get(resource_path('views/ampp/connections/connections.md')));

        return view('ampp.connections.index', [
            'connections' => $connections,
            'content' => $connectionsContent
        ]);
    }
}
