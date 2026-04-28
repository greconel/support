<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\Actions\Clearfacts\UploadToClearfactsAction;
use App\Enums\ClearfactsInvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Expense;

class UploadExpenseToClearfactsController extends Controller
{
    public function __invoke(Expense $expense, UploadToClearfactsAction $uploadToClearfactsAction)
    {
        $pdf = $expense->getFirstMedia();

        if (! $pdf){
            session()->flash('error', __('We can not upload an expense to Clearfacts if it doesn\'t have a PDF.'));
            return back();
        }

        $response = $uploadToClearfactsAction->execute(
            ClearfactsInvoiceType::Purchase,
            $pdf->getPath(),
            "{$expense->name}.pdf",
            $expense->clearfacts_comment
        );

        if ($response->failed() || $response->collect()->has('errors')){
            session()->flash('error', __('Something went wrong, please contact our support.'));
            return redirect()->action(ShowExpenseController::class, $expense);
        }

        $data = $response->collect();

        $uuid = data_get($data, 'data.uploadFile.uuid');

        if (! $uuid){
            session()->flash('error', __('Expense is uploaded but clearfacts\' response was incorrect, please contact our support.'));
            return redirect()->action(ShowExpenseController::class, $expense);
        }

        $expense->update([
            'clearfacts_id' => $uuid,
            'sent_to_clearfacts_at' => now()
        ]);

        session()->flash('success', __('Expense uploaded to clearfacts.'));
        return redirect()->action(ShowExpenseController::class, $expense);
    }
}
