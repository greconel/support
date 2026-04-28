<?php

namespace App\Http\Controllers\Ampp\GdprRegisters;

use App\Http\Controllers\Controller;
use App\Models\GdprRegister;

class DestroyGdprRegisterController extends Controller
{
    public function __invoke(GdprRegister $gdprRegister)
    {
        $this->authorize('delete', $gdprRegister);

        $gdprRegister->delete();

        session()->flash('success', __('GDPR register deleted'));

        return redirect()->action(IndexGdprRegisterController::class);
    }
}
