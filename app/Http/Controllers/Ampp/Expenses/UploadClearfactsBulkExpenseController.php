<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\Actions\Clearfacts\UploadToClearfactsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Expenses\UploadClearfactsBulkExpenseRequest;
use App\Jobs\UploadBulkExpensesToClearfactsJob;
use App\Models\Expense;

class UploadClearfactsBulkExpenseController extends Controller
{
    public function __invoke(UploadClearfactsBulkExpenseRequest $request, UploadToClearfactsAction $uploadToClearfactsAction)
    {
        $expenses = Expense::whereIn('id', $request->input('expenses'))->get();

        dispatch(new UploadBulkExpensesToClearfactsJob(auth()->user(), $expenses));

        session()->flash('success', __('Expenses are uploading to Clearfacts.'));

        return redirect()->action(IndexClearfactsBulkExpenseController::class);
    }
}
