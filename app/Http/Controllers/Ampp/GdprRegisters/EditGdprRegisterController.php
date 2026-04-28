<?php

namespace App\Http\Controllers\Ampp\GdprRegisters;

use App\Http\Controllers\Controller;
use App\Models\GdprRegister;

class EditGdprRegisterController extends Controller
{
    public function __invoke(GdprRegister $gdprRegister)
    {
        $this->authorize('update', $gdprRegister);

        return view('ampp.gdprRegisters.edit', [
            'register' => $gdprRegister
        ]);
    }
}
