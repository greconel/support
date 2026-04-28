<?php

namespace App\Http\Controllers\Ampp\BillingReports;

use App\Http\Controllers\Controller;

class IndexBillingReportController extends Controller
{
    public function __invoke()
    {
        abort_if(auth()->user()->cannot('view billing reports'), 403);

        return view('ampp.billingReports.index');
    }
}
