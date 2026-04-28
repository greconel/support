<?php

namespace App\Http\Controllers\Ampp\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;

class ToggleClearfactsController extends Controller
{
    public function __invoke(Supplier $supplier)
    {
        $this->authorize('update', $supplier);

        $supplier->update(['is_disabled_for_clearfacts' => ! $supplier->is_disabled_for_clearfacts]);

        session()->flash(
            'success',
            __('Clearfacts bulk is now :status for this supplier', ['status' => $supplier->refresh()->is_disabled_for_clearfacts ? __('disabled') : __('enabled')])
        );

        return redirect()->action(ShowSupplierController::class, $supplier);
    }
}
