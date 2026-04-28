<?php

namespace App\Http\Controllers\Ampp\GdprAudits;

use App\Http\Controllers\Controller;
use App\Models\GdprAudit;

class DestroyGdprAuditController extends Controller
{
    public function __invoke(GdprAudit $gdprAudit)
    {
        $this->authorize('delete', $gdprAudit);

        $gdprAudit->delete();

        session()->flash('success', __('GDPR audit deleted'));

        return redirect()->action(IndexGdprAuditController::class);
    }
}
