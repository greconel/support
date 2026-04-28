<?php

namespace App\Http\Controllers\Admin\PassportClients;

use App\DataTables\Admin\PassportClientDataTable;
use App\Http\Controllers\Controller;
use App\Policies\PassportClientPolicy;

class IndexPassportClientController extends Controller
{
    public function __invoke(PassportClientDataTable $dataTable)
    {
        $this->authorize('viewAny', PassportClientPolicy::class);

        return $dataTable->render('admin.passportClients.index');
    }
}
