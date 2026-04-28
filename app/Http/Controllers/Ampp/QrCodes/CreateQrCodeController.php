<?php

namespace App\Http\Controllers\Ampp\QrCodes;

use App\Http\Controllers\Controller;
use App\Models\QrCode;

class CreateQrCodeController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', QrCode::class);

        return view('ampp.qrCodes.create');
    }
}
