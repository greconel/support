<?php

namespace App\Http\Controllers\Ampp\GdprRegisters;

use App\DataTables\Ampp\GdprRegisterDataTable;
use App\Http\Controllers\Controller;
use App\Models\GdprRegister;

class IndexGdprRegisterController extends Controller
{
    public function __invoke(GdprRegisterDataTable $dataTable)
    {
        $this->authorize('viewAny', GdprRegister::class);

        return $dataTable->render('ampp.gdprRegisters.index');
    }
}
