<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UpdateUserRequest;
use App\Mail\PasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UpdateUserController extends Controller
{
    public function __invoke(UpdateUserRequest $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $this->authorize('update', $user);

        $this->validate($request, [

        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'motion_user_id' => $request->input('motion_user_id'),
        ]);

        $user->syncRoles(array_map('intval', (array) $request->input('roles', [])));

        if ($request->has('password_resend')){
            $password = Str::random();
            $user->update(['password' => Hash::make($password)]);

            Mail::to($user)->queue(new PasswordMail($user, $password));
        }

        session()->flash('success', __('Edited user'));

        return redirect()->action(IndexUserController::class);
    }
}
