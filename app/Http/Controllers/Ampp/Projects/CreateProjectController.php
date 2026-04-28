<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;

class CreateProjectController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Project::class);

        $users = User::all()
            ->pluck('name', 'id')
            ->sort()
            ->toArray();

        $clients = Client::all()
            ->pluck('full_name_with_company', 'id')
            ->prepend(__('-- No client --'), '')
            ->toArray();

        return view('ampp.projects.create', [
            'users' => $users,
            'clients' => $clients
        ]);
    }
}
