<?php

namespace App\Http\Controllers\Ampp\Suppliers;

use App\Classes\CountriesHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Suppliers\StoreSupplierRequest;
use App\Models\Supplier;

class StoreSupplierController extends Controller
{
    public function __invoke(StoreSupplierRequest $request)
    {
        $this->authorize('create', Supplier::class);

        $input = $request->all();

        if ($request->has('country')) {
            $countriesHelper = new CountriesHelper();
            $input['country_code'] = $countriesHelper->findCca2($request->input('country'));
        }

        $input['is_general'] = $request->has('is_general');

        $supplier = Supplier::create($input);

        session()->flash('success', __('New supplier created'));

        return redirect()->action(ShowSupplierController::class, $supplier);
    }
}
