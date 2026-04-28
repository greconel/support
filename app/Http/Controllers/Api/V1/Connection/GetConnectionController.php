<?php

namespace App\Http\Controllers\Api\V1\Connection;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConnectionCollection;
use App\Models\Connection;

class GetConnectionController extends Controller
{
    public function __invoke()
    {
        return new ConnectionCollection(
            Connection::all()
        );
    }
}