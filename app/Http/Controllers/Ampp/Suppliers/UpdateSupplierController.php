<?php

namespace App\Http\Controllers\Ampp\Suppliers;

use App\Classes\CountriesHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Suppliers\UpdateSupplierRequest;
use App\Models\Supplier;

class UpdateSupplierController extends Controller
{
    public function __invoke(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);

        $input = $request->all();

        if ($request->has('country')) {
            $countriesHelper = new CountriesHelper();
            $input['country_code'] = $countriesHelper->findCca2($request->input('country'));
        }

        $input['is_general'] = $request->has('is_general');

        $supplier->update($input);

        session()->flash('success', __('Supplier updated'));

        return redirect()->action(ShowSupplierController::class, $supplier);
    }
}
