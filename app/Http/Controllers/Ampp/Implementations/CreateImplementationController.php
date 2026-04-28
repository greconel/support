<?php

namespace App\Http\Controllers\Ampp\Implementations;

use App\Http\Controllers\Controller;
use App\Models\Implementation;

class CreateImplementationController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Implementation::class);

        return view('ampp.implementations.create');
    }
}
