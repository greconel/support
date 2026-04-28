<?php

namespace App\Http\Controllers\Ampp\ClientContacts;

use App\Http\Controllers\Ampp\Clients\ShowClientController;
use App\Http\Controllers\Controller;
use App\Models\ClientContact;

class DestroyClientContactController extends Controller
{
    public function __invoke(ClientContact $clientContact)
    {
        $this->authorize('delete', $clientContact);

        $clientContact->delete();

        session()->flash('success', __('Goodbye contact! 😥'));

        return redirect()
            ->action(ShowClientController::class, $clientContact->client)
            ->with('success', __('Successfully deleted contact'))
        ;
    }
}
