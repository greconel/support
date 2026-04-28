<?php

namespace App\Http\Controllers\Ampp\Maps;

use App\Http\Controllers\Controller;
use App\Models\Client;

class ClientMapController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view clients');
    }

    public function __invoke()
    {
        return view('ampp.clientsMap.index', [
            'clients' => Client::all()
        ]);
    }
}
