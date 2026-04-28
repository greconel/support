<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('gdprChecklist', function (BreadcrumbTrail $trail){
    $trail
        ->push(__('PrivacyPro'))
        ->push(__('Checklists'), action(\App\Http\Controllers\Ampp\GdprChecklists\IndexGdprChecklistController::class))
        ->push(__('Edit'))
    ;
});

Breadcrumbs::for('createGdprRegister', function (BreadcrumbTrail $trail){
    $trail
        ->push(__('PrivacyPro'))
        ->push(__('Registers'), action(\App\Http\Controllers\Ampp\GdprRegisters\IndexGdprRegisterController::class))
        ->push(__('Create new GDPR register'), action(\App\Http\Controllers\Ampp\GdprRegisters\CreateGdprRegisterController::class))
    ;
});

Breadcrumbs::for('editGdprRegister', function (BreadcrumbTrail $trail, \App\Models\GdprRegister $gdprRegister){
    $trail
        ->push(__('PrivacyPro'))
        ->push(__('Registers'), action(\App\Http\Controllers\Ampp\GdprRegisters\IndexGdprRegisterController::class))
        ->push(__('Edit'), action(\App\Http\Controllers\Ampp\GdprRegisters\EditGdprRegisterController::class, $gdprRegister))
    ;
});

Breadcrumbs::for('createGdprMessage', function (BreadcrumbTrail $trail){
    $trail
        ->push(__('PrivacyPro'))
        ->push(__('Messages'), action(\App\Http\Controllers\Ampp\GdprMessages\IndexGdprMessageController::class))
        ->push(__('Create new GDPR message'), action(\App\Http\Controllers\Ampp\GdprMessages\CreateGdprMessageController::class))
    ;
});

Breadcrumbs::for('editGdprMessage', function (BreadcrumbTrail $trail, \App\Models\GdprMessage $gdprMessage){
    $trail
        ->push(__('PrivacyPro'))
        ->push(__('Messages'), action(\App\Http\Controllers\Ampp\GdprMessages\IndexGdprMessageController::class))
        ->push(__('Edit'), action(\App\Http\Controllers\Ampp\GdprMessages\EditGdprMessageController::class, $gdprMessage))
    ;
});

Breadcrumbs::for('createGdprAudit', function (BreadcrumbTrail $trail){
    $trail
        ->push(__('PrivacyPro'))
        ->push(__('Audits'), action(\App\Http\Controllers\Ampp\GdprAudits\IndexGdprAuditController::class))
        ->push(__('Create new GDPR audit'), action(\App\Http\Controllers\Ampp\GdprAudits\CreateGdprAuditController::class))
    ;
});

Breadcrumbs::for('editGdprAudit', function (BreadcrumbTrail $trail, \App\Models\GdprAudit $gdprAudit){
    $trail
        ->push(__('PrivacyPro'))
        ->push(__('Audits'), action(\App\Http\Controllers\Ampp\GdprAudits\IndexGdprAuditController::class))
        ->push(__('Edit'), action(\App\Http\Controllers\Ampp\GdprAudits\EditGdprAuditController::class, $gdprAudit))
    ;
});
