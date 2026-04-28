<?php

namespace App\Http\Controllers\Ampp\GdprRegisters;

use App\Http\Controllers\Controller;
use App\Models\GdprRegister;

class CreateGdprRegisterController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', GdprRegister::class);

        return view('ampp.gdprRegisters.create');
    }
}
