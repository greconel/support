<?php

namespace App\Http\Controllers\Ampp\QrCodes;

use App\Http\Controllers\Controller;
use App\Models\QrCode;

class DestroyQrCodeController extends Controller
{
    public function __invoke(QrCode $qrCode)
    {
        $this->authorize('delete', $qrCode);

        $qrCode->delete();
    }
}
