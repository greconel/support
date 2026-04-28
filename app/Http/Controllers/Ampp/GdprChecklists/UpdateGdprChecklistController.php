<?php

namespace App\Http\Controllers\Ampp\GdprChecklists;

use App\Http\Controllers\Controller;
use App\Models\GdprChecklist;
use Illuminate\Http\Request;

class UpdateGdprChecklistController extends Controller
{
    public function __invoke(Request $request, GdprChecklist $gdprChecklist)
    {
        $this->authorize('update', $gdprChecklist);

        $gdprChecklist->update($request->all());

        return redirect()->back();
    }
}
