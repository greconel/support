<?php

namespace App\Http\Controllers\Ampp\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Projects\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateProjectController extends Controller
{
    public function __invoke(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $data = $request->validated();
        
        unset($data['users']);

        $data['is_general'] = $request->has('is_general');

        $project->update($data);

        $project->users()->sync($request->input('users'));

        session()->flash('success', __('Successfully edited project'));

        return redirect()->back();
    }
}
