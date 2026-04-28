<?php

namespace App\Http\Controllers\Ampp\Connections;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use Illuminate\Http\Request;

class UpdateConnectionController extends Controller
{
    public function __invoke(Request $request, Connection $connection)
    {
        $this->authorize('update', $connection);

        $connection->update(['in_use' => $request->has('in_use')]);

        return redirect()->back();
    }
}
