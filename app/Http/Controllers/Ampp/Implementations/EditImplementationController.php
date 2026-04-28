<?php

namespace App\Http\Controllers\Ampp\Implementations;

use App\Http\Controllers\Controller;
use App\Models\Implementation;

class EditImplementationController extends Controller
{
    public function __invoke(Implementation $implementation)
    {
        $this->authorize('update', $implementation);

        return view('ampp.implementations.edit', compact('implementation'));
    }
}
