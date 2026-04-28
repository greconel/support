<?php

namespace App\Http\Controllers\Ampp\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;

class DestroySupplierController extends Controller
{
    public function __invoke(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);

        $supplier->delete();

        session()->flash('success', __('Goodbye supplier! 😥'));

        return redirect()->action(IndexSupplierController::class);
    }
}
