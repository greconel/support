<?php

namespace App\Http\Controllers\Ampp\GdprAudits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\GdprAudits\StoreGdprAuditRequest;
use App\Models\GdprAudit;
use Illuminate\Http\Request;

class StoreGdprAuditController extends Controller
{
    public function __invoke(StoreGdprAuditRequest $request)
    {
        $this->authorize('create', GdprAudit::class);

        GdprAudit::create($request->all());

        session()->flash('success', __('GDPR audit created'));

        return redirect()->action(IndexGdprAuditController::class);
    }
}
