<?php

namespace App\Http\Controllers\Admin\PassportClients;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PassportClients\StorePassportClientRequest;
use App\Policies\PassportClientPolicy;
use Laravel\Passport\ClientRepository;

class StorePassportClientController extends Controller
{
    public function __construct(
        protected ClientRepository $clientRepository
    ) {}

    public function __invoke(StorePassportClientRequest $request)
    {
        $this->authorize('create', PassportClientPolicy::class);

        $this
            ->clientRepository
            ->create(
                $request->input('user_id'),
                $request->input('name'),
                $request->input('redirect') ?? ''
            )
        ;

        session()->flash('success', __('Created new passport client'));

        return redirect()->action(IndexPassportClientController::class);
    }
}
