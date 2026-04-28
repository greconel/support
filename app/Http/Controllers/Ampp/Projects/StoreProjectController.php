<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Projects\StoreProjectRequest;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreProjectController extends Controller
{
    public function __invoke(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $data = $request->validated();

        unset($data['users']);

        $data['is_general'] = $request->has('is_general');

        $project = Project::create($data);

        $project->users()->attach($request->input('users'));

        return redirect()
            ->action(ShowProjectOverviewController::class, $project)
            ->with('success', __('Successfully created project'))
        ;
    }
}
