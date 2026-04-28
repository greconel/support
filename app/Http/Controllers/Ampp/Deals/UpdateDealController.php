<?php

namespace App\Http\Controllers\Ampp\Deals;

use App\Enums\ClientType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Deals\UpdateDealRequest;
use App\Models\Client;
use App\Models\Deal;

class UpdateDealController extends Controller
{
    public function __invoke(UpdateDealRequest $request, Deal $deal)
    {
        $this->authorize('update', $deal);

        if ($request->input('client_id') == 'new_lead'){
            $lead = Client::create([
                'type' => ClientType::Lead,
                'first_name' => $request->input('lead_first_name'),
                'last_name' => $request->input('lead_last_name'),
                'company' => $request->input('lead_company'),
                'vat' => $request->input('lead_vat'),
                'email' => $request->input('lead_email'),
                'phone' => $request->input('lead_phone'),
            ]);
        } else {
            $lead = Client::find($request->input('client_id'));
        }

        $deal->update([
            'name' => $request->input('name'),
            'client_id' => $lead?->id,
            'due_date' => $request->input('due_date'),
            'chance_of_success' => $request->input('chance_of_success'),
            'expected_revenue' => $request->input('expected_revenue'),
            'expected_start_date' => $request->input('expected_start_date'),
            'description' => $request->input('description'),
        ]);

        session()->flash('success', __('Deal has been updated'));

        return redirect()->action(ShowDealController::class, $deal);
    }
}
