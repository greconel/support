<?php

namespace App\Http\Controllers\Ampp\Clients;

use App\Http\Controllers\Controller;
use App\Models\Client;

class DestroyClientController extends Controller
{
    public function __invoke($id)
    {
        $client = Client::withTrashed()->findOrFail($id);

        $this->authorize('delete', $client);

        $client->forceDelete();

        session()->flash('success', __('Goodbye client! 😥'));

        return redirect()->action(IndexClientController::class);
    }
}
