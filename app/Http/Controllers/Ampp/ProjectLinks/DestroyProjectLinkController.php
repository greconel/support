<?php

namespace App\Http\Controllers\Ampp\ProjectLinks;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLink;

use function __;
use function redirect;
use function session;

class DestroyProjectLinkController extends Controller
{
    public function __invoke(Project $project, ProjectLink $projectLink)
    {
        $this->authorize('view', $projectLink->project);

        $project->links()->findOrFail($projectLink->id)->delete();

        session()->flash('success', __('Project link deleted'));

        return redirect()->back();
    }
}
