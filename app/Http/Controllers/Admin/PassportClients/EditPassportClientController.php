<?php

namespace App\Http\Controllers\Admin\PassportClients;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Policies\PassportClientPolicy;
use Laravel\Passport\Client;

class EditPassportClientController extends Controller
{
    public function __invoke(Client $passportClient)
    {
        $this->authorize('update', PassportClientPolicy::class);

        if ($passportClient->revoked) {
            session()->flash('error',  __('Client is revoked'));

            return redirect()->back();
        }

        $users = User::all()
            ->pluck('name', 'id')
            ->prepend(__('-- EMPTY --'), null)
            ->toArray()
        ;

        return view('admin.passportClients.edit', [
            'passportClient' => $passportClient,
            'users' => $users
        ]);
    }
}
