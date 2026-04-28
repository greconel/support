<?php

namespace App\Http\Controllers\Ampp\TimeRegistrations;

use App\DataTables\Ampp\TimeRegistrationDataTable;
use App\Http\Controllers\Controller;
use App\Models\TimeRegistration;

class IndexTableTimeRegistrationController extends Controller
{
    public function __invoke(TimeRegistrationDataTable $dataTable)
    {
        $this->authorize('viewAny', TimeRegistration::class);

        return $dataTable
            ->with([
                'user' => request()->input('user'),
                'project' => request()->input('project')
            ])
            ->render('ampp.timeRegistrations.indexTable')
        ;
    }
}
