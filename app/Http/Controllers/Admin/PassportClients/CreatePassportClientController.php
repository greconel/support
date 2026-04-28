<?php

namespace App\Http\Controllers\Admin\PassportClients;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Policies\PassportClientPolicy;

class CreatePassportClientController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', PassportClientPolicy::class);

        $users = User::all()
            ->pluck('name', 'id')
            ->prepend(__('-- EMPTY --'), null)
            ->toArray()
        ;

        return view('admin.passportClients.create', [
            'users' => $users
        ]);
    }
}
