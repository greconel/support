<?php

namespace App\Http\Controllers\Ampp\Profile;

use App\Http\Requests\Ampp\Profile\UpdateProfileRequest;
use Illuminate\Support\Facades\Cache;

class UpdateProfileController
{
    public function __invoke(UpdateProfileRequest $request)
    {
        auth()->user()->update([
            'name' => $request->input('name', auth()->user()->name),
            'email' => $request->input('email', auth()->user()->email),
        ]);

        if ($request->hasFile('avatar')){
            auth()->user()->clearMediaCollection('avatar');

            auth()
                ->user()
                ->addMediaFromRequest('avatar')
                ->toMediaCollection('avatar', 'private')
            ;

            Cache::forget('avatar_' . auth()->id());
        }

        session()->flash('success', __('Profile updated!'));

        return redirect()->back();
    }
}
