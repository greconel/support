<?php

namespace App\Http\Controllers\Ampp\QrCodes;

use App\Http\Controllers\Controller;
use App\Models\QrCode;

class ShowQrCodeController extends Controller
{
    public function __invoke(QrCode $qrCode)
    {
        $this->authorize('view', $qrCode);

        if (!$qrCode->getFirstMedia('image')){
            return redirect()->back();
        }

        return redirect()->action(\App\Http\Controllers\Media\DownloadMediaController::class, $qrCode->getFirstMedia('image'));
    }
}
