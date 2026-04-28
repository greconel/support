<?php

namespace App\Http\Controllers\Ampp\GdprChecklists;

use App\Http\Controllers\Controller;
use App\Models\GdprChecklist;

class EditGdprChecklistController extends Controller
{
    public function __invoke(GdprChecklist $gdprChecklist)
    {
        $this->authorize('update', $gdprChecklist);

        return view('ampp.gdprChecklists.edit', [
            'checklist' => $gdprChecklist
        ]);
    }
}
