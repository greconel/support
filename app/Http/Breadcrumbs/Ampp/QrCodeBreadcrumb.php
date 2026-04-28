<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexQrCode', function (BreadcrumbTrail $trail){
    $trail->push(__('Qr codes'), action(\App\Http\Controllers\Ampp\QrCodes\IndexQrCodeController::class));
});

Breadcrumbs::for('createQrCode', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexQrCode')
        ->push(__('Create new QR code'), action(\App\Http\Controllers\Ampp\QrCodes\CreateQrCodeController::class))
    ;
});

Breadcrumbs::for('showQrCode', function (BreadcrumbTrail $trail, \App\Models\QrCode $qrCode){
    $trail
        ->parent('indexQrCode')
        ->push(
            $qrCode->name,
            action(\App\Http\Controllers\Ampp\QrCodes\ShowQrCodeController::class, $qrCode)
        )
    ;
});
