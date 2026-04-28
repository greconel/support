<?php

namespace App\Http\Controllers\Ampp\InvoiceCategories;

use App\Http\Controllers\Controller;
use App\Models\InvoiceCategory;
use Illuminate\Http\Request;

class StoreInvoiceCategoryController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        InvoiceCategory::create(['name' => $request->input('name')]);

        session()->flash('success', __('New invoice category created'));

        return redirect()->action(IndexInvoiceCategoryController::class);
    }
}
