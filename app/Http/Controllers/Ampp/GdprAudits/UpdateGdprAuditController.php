<?php

namespace App\Http\Controllers\Ampp\GdprAudits;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\GdprAudits\UpdateGdprAuditRequest;
use App\Models\GdprAudit;
use Illuminate\Http\Request;

class UpdateGdprAuditController extends Controller
{
    public function __invoke(UpdateGdprAuditRequest $request, GdprAudit $gdprAudit)
    {
        $this->authorize('update', $gdprAudit);

        $gdprAudit->update($request->all());

        session()->flash('success', __('GDPR audit edited'));

        return redirect()->action(IndexGdprAuditController::class);
    }
}
