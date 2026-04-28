<?php

namespace App\Http\Controllers\Admin\Connections;

use App\Http\Controllers\Controller;
use App\Models\Connection;

class CreateConnectionController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Connection::class);

        return view('admin.connections.create');
    }
}