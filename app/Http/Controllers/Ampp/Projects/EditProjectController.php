<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;

class EditProjectController extends Controller
{
    public function __invoke($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $this->authorize('update', $project);

        $users = User::all()
            ->pluck('name', 'id')
            ->sort()
            ->toArray();

        $clients = Client::all()
            ->pluck('full_name_with_company', 'id')
            ->prepend(__('-- No client --'), '')
            ->toArray();

        return view('ampp.projects.show.edit', [
            'project' => $project,
            'users' => $users,
            'clients' => $clients
        ]);
    }
}
