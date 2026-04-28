<?php

namespace App\Http\Controllers\Ampp\GdprChecklists;

use App\Http\Controllers\Controller;
use App\Models\GdprChecklist;

class IndexGdprChecklistController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', GdprChecklist::class);

        return view('ampp.gdprChecklists.index');
    }
}
