<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StoreUserRequest;
use App\Mail\PasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StoreUserController extends Controller
{
    public function __invoke(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $password = Str::random();

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($password)
        ]);

        $user->assignRole(array_map('intval', (array) $request->input('roles', [])));

        Mail::to($user)->queue(new PasswordMail($user, $password));

        session()->flash('success', 'Created new user');

        return redirect()->action(IndexUserController::class);
    }
}
