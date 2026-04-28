<?php

namespace App\Http\Controllers\Ampp\GdprRegisters;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\GdprRegisters\UpdateGdprRegisterRequest;
use App\Models\GdprRegister;
use Illuminate\Http\Request;

class UpdateGdprRegisterController extends Controller
{
    public function __invoke(UpdateGdprRegisterRequest $request, GdprRegister $gdprRegister)
    {
        $this->authorize('update', $gdprRegister);

        $gdprRegister->update($request->all());

        return redirect()
            ->action(IndexGdprRegisterController::class)
            ->with('success', __('Successfully edited GDPR register'))
        ;
    }
}
