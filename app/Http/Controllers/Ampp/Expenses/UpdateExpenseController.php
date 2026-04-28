<?php

namespace App\Http\Controllers\Ampp\Expenses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Expenses\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\Supplier;
use Illuminate\Support\Str;

class UpdateExpenseController extends Controller
{
    public function __invoke(UpdateExpenseRequest $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $supplier = Supplier::find($request->input('supplier_id'));

        $expense->update([
            'name' => $request->input('name') ?? Str::kebab(strtolower($supplier->company)) . '_' . $request->input('invoice_date'),
            'supplier_id' => $request->input('supplier_id'),
            'invoice_date' => $request->input('invoice_date'),
            'invoice_number' => $request->input('invoice_number'),
            'invoice_ogm' => $request->input('invoice_ogm'),
            'amount_excluding_vat' => $request->input('amount_excluding_vat'),
            'amount_including_vat' => $request->input('amount_including_vat'),
            'amount_vat' => $request->input('amount_vat'),
            'comment' => $request->input('comment'),
            'various_transaction_category' => $request->input('various_transaction_category'),
            'invoice_category_id' => $request->input('invoice_category_id'),
        ]);

        if ($request->has('file')){
            $expense
                ->addMediaFromRequest('file')
                ->usingName($request->file('file')->getClientOriginalName())
                ->toMediaCollection(diskName: 'private')
            ;
        } else {
            $expense->clearMediaCollection();
        }

        session()->flash('success', __('Expense updated'));

        return redirect()->action(ShowExpenseController::class, $expense);
    }
}
