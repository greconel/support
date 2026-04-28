<?php

namespace App\Http\Controllers\Ampp\GdprRegisters;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\GdprRegisters\StoreGdprRegisterRequest;
use App\Models\GdprRegister;
use Illuminate\Http\Request;

class StoreGdprRegisterController extends Controller
{
    public function __invoke(StoreGdprRegisterRequest $request)
    {
        $this->authorize('create', GdprRegister::class);

        GdprRegister::create($request->all());

        return redirect()
            ->action(IndexGdprRegisterController::class)
            ->with('success', __('Successfully created GDPR register'))
        ;
    }
}
