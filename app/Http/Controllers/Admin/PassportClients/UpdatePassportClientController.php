<?php

namespace App\Http\Controllers\Admin\PassportClients;

use App\Http\Controllers\Controller;
use App\Policies\PassportClientPolicy;
use Illuminate\Http\Request;
use Laravel\Passport\Client;

class UpdatePassportClientController extends Controller
{
    public function __invoke(Request $request, Client $passportClient)
    {
        $this->authorize('update', PassportClientPolicy::class);

        $passportClient
            ->forceFill([
                'user_id' => $request->input('user_id'),
                'name' => $request->input('name'),
                'redirect' => $request->input('redirect') ?? ''
            ])
            ->save()
        ;

        session()->flash('success', __('Passport client updated'));

        return redirect()->action(IndexPassportClientController::class);
    }
}
