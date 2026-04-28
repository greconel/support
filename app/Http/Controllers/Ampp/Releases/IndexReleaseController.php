<?php

namespace App\Http\Controllers\Ampp\Releases;

use App\Http\Controllers\Controller;
use App\Models\Release;

class IndexReleaseController extends Controller
{
    public function __invoke()
    {
        $releases = Release::orderByDesc('released_at')->get();

        return view('ampp.releases.index', [
            'releases' => $releases
        ]);
    }
}
