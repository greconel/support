<?php

namespace App\Http\Controllers\Ampp\ClientContacts;

use App\Http\Controllers\Ampp\Clients\ShowClientController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\ClientContacts\StoreClientContactRequest;
use App\Models\Client;
use App\Models\ClientContact;

class StoreClientContactController extends Controller
{
    public function __invoke(StoreClientContactRequest $request, Client $client)
    {
        $this->authorize('viewAny', ClientContact::class);

        $client->contacts()->create($request->all());

        return redirect()
            ->action(ShowClientController::class, $client)
            ->with('success', __('Successfully created contact'))
        ;
    }
}
