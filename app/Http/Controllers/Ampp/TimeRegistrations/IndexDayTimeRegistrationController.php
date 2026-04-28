<?php

namespace App\Http\Controllers\Ampp\TimeRegistrations;

use App\DataTables\Ampp\TimeRegistrationDataTable;
use App\Http\Controllers\Controller;
use App\Models\TimeRegistration;

class IndexDayTimeRegistrationController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', TimeRegistration::class);

        return view('ampp.timeRegistrations.indexDay');
    }
}
