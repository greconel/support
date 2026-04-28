<?php

namespace App\Http\Controllers\Admin\PassportClients;

use App\Http\Controllers\Controller;
use App\Policies\PassportClientPolicy;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

class DestroyPassportClientController extends Controller
{
    public function __construct(
        protected ClientRepository $clientRepository
    ) {}

    public function __invoke(Client $passportClient)
    {
        $this->authorize('delete', PassportClientPolicy::class);

        $this->clientRepository->delete($passportClient);

        session()->flash('success', __('Goodbye passport client! 😥'));

        return redirect()->action(IndexPassportClientController::class);
    }
}
