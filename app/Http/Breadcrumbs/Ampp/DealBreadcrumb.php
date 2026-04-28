<?php

use App\Http\Controllers\Ampp\Deals\EditDealController;
use App\Http\Controllers\Ampp\Deals\IndexDealBoardController;
use App\Http\Controllers\Ampp\Deals\IndexDealTableController;
use App\Http\Controllers\Ampp\Deals\ShowDealController;
use App\Models\Deal;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexDeal', function (BreadcrumbTrail $trail){
    $trail->push(__('Leads (Board)'), $url ?? action(IndexDealBoardController::class));
    $trail->push(__('Leads (Table)'), $url ?? action(IndexDealTableController::class));
});

Breadcrumbs::for('showDeal', function (BreadcrumbTrail $trail, Deal $deal){
    $trail
        ->parent('indexDeal')
        ->push($deal->name, action(ShowDealController::class, $deal))
    ;
});

Breadcrumbs::for('editDeal', function (BreadcrumbTrail $trail, Deal $deal){
    $trail
        ->parent('showDeal', $deal)
        ->push(
            __('Edit'),
            action(EditDealController::class, $deal)
        )
    ;
});
