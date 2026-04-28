<?php

namespace App\Http\Controllers\Ampp\Releases;

use App\Http\Controllers\Controller;
use App\Models\Release;

class MarkReleaseAsCurrentReleaseController extends Controller
{
    public function __invoke(Release $release)
    {
        $this->authorize('update', $release);

        Release::all()->each(fn (Release $r) => $r->update(['is_current_release' => false]));

        $release->refresh()->update(['is_current_release' => true]);

        session()->flash('success', __(':tag is now the current release', ['tag' => $release->tag_name]));

        return redirect()->action(IndexReleaseController::class);
    }
}
