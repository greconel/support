<?php

namespace App\Http\Controllers\Ampp\Implementations;

use App\Http\Controllers\Controller;
use App\Models\Implementation;

class DestroyImplementationController extends Controller
{
    public function __invoke(Implementation $implementation)
    {
        $this->authorize('delete', $implementation);

        // Disable the beacon key if one exists
        if ($implementation->beaconKey) {
            $implementation->beaconKey->disable();
        }

        $implementation->delete();

        session()->flash('success', __('Implementation archived successfully'));

        return redirect()->action(IndexImplementationController::class);
    }
}
