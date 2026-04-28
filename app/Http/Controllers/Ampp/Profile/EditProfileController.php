<?php

namespace App\Http\Controllers\Ampp\Profile;

class EditProfileController
{
    public function __invoke()
    {
        return view('ampp.profile.edit', [
            'user' => auth()->user()
        ]);
    }
}
