<?php

namespace App\Http\Controllers\Api\V1\Connection;

use App\Http\Controllers\Controller;
use App\Models\Connection;

class GetLatestConnectionController extends Controller
{
    public function __invoke()
    {
        return Connection::latest('id')->first()->id;
    }
}