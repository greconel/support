<?php

namespace App\Http\Controllers\Ampp\QrCodes;

use App\DataTables\Ampp\QrCodeDataTable;
use App\Http\Controllers\Controller;
use App\Models\QrCode;

class IndexQrCodeController extends Controller
{
    public function __invoke(QrCodeDataTable $dataTable)
    {
        $this->authorize('viewAny', QrCode::class);

        return $dataTable->render('ampp.qrCodes.index');
    }
}
