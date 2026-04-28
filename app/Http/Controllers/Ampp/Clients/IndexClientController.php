<?php

namespace App\Http\Controllers\Ampp\Clients;

use App\DataTables\Ampp\ClientDataTable;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class IndexClientController extends Controller
{
    public function __invoke(ClientDataTable $dataTable, Request $request)
    {
        $this->authorize('viewAny', Client::class);

        return $dataTable
            ->with([
                'archive' => $request->get('archive'),
                'type' => $request->get('type')
            ])
            ->render('ampp.clients.index', [
                'archiveCount' => Client::query()->onlyTrashed()->count()
            ])
        ;
    }
}
