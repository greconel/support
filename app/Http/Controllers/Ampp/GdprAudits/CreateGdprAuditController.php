<?php

namespace App\Http\Controllers\Ampp\GdprAudits;

use App\Http\Controllers\Controller;
use App\Models\GdprAudit;

class CreateGdprAuditController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', GdprAudit::class);

        return view('ampp.gdprAudits.create');
    }
}
