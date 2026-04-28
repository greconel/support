<?php

namespace App\Http\Controllers\Ampp\Profile;

use App\Http\Requests\Ampp\Profile\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController
{
    public function __invoke(UpdatePasswordRequest $request)
    {
        auth()->user()->update([
            'password' => Hash::make($request->input('password'))
        ]);

        session()->flash('success', __('Password is updated!'));

        return redirect()->back();
    }
}
