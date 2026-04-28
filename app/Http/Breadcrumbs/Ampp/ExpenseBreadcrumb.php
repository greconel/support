<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexExpense', function (BreadcrumbTrail $trail){
    $trail->push(__('Expenses'), action(\App\Http\Controllers\Ampp\Expenses\IndexExpenseController::class));
});

Breadcrumbs::for('createExpense', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexExpense')
        ->push(__('Create new expense'), action(\App\Http\Controllers\Ampp\Expenses\CreateExpenseController::class))
    ;
});

Breadcrumbs::for('showExpense', function (BreadcrumbTrail $trail, \App\Models\Expense $expense){
    $trail
        ->parent('indexExpense')
        ->push($expense->name, action(\App\Http\Controllers\Ampp\Expenses\ShowExpenseController::class, $expense))
    ;
});

Breadcrumbs::for('editExpense', function (BreadcrumbTrail $trail, \App\Models\Expense $expense){
    $trail
        ->parent('showExpense', $expense)
        ->push(
            __('Edit'),
            action(\App\Http\Controllers\Ampp\Expenses\EditExpenseController::class, $expense)
        )
    ;
});

Breadcrumbs::for('SelectExpensesForClearfacts', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexExpense')
        ->push(__('Expenses Clearfacts bulk upload'))
    ;
});
