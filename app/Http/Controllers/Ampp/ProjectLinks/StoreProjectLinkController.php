<?php

namespace App\Http\Controllers\Ampp\ProjectLinks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Projects\StoreProjectLinkRequest;
use App\Models\Project;

use function __;
use function redirect;
use function session;

class StoreProjectLinkController extends Controller
{
    public function __invoke(StoreProjectLinkRequest $request, $id)
    {
        $project = Project::withTrashed()->findOrFail($id);

        $this->authorize('view', $project);

        $project->links()->create([
            'name' => $request->input('name'),
            'href' => $request->input('href'),
        ]);

        session()->flash('success', __('New link created for project'));

        return redirect()->back();
    }
}
