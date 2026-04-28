<?php

namespace App\Http\Controllers\Ampp\ClientContacts;

use App\Http\Controllers\Ampp\Clients\ShowClientController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\ClientContacts\UpdateClientContactRequest;
use App\Models\ClientContact;

class UpdateClientContactController extends Controller
{
    public function __invoke(UpdateClientContactRequest $request, ClientContact $clientContact)
    {
        $this->authorize('update', $clientContact);

        $clientContact->update($request->all());

        return redirect()
            ->action(ShowClientController::class, $clientContact->client)
            ->with('success', __('Successfully edited contact'))
        ;
    }
}
