<?php

namespace App\Http\Controllers\Ampp\Releases;

use App\Actions\Releases\ImportReleasesAction;
use App\Http\Controllers\Controller;
use App\Models\Release;

class ImportReleasesController extends Controller
{
    public function __invoke(ImportReleasesAction $importReleasesAction)
    {
        $this->authorize('create', Release::class);

        try {
            $importReleasesAction->execute();
        } catch (\Exception){
            session()->flash('error', __('Something went wrong'));
        }

        session()->flash('success', __('Releases have been imported'));

        return redirect()->action(IndexReleaseController::class);
    }
}
