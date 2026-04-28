<?php

namespace App\Http\Controllers\Ampp\Quotations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Quotations\StoreQuotationRequest;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StoreQuotationController extends Controller
{
    public function __invoke(StoreQuotationRequest $request)
    {
        $this->authorize('create', Quotation::class);

        $quotation = Quotation::create([
            'client_id' => $request->input('client_id'),
            'expiration_date' => Carbon::createFromFormat('d/m/Y', $request->input('expiration_date')),
            'number' => $request->input('number'),
            'custom_created_at' => Carbon::createFromFormat('d/m/Y', $request->input('custom_created_at')),
        ]);

        if ($request->input('deal_id')){
            $deal = Deal::find($request->input('deal_id'));

            $deal->update(['quotation_id' => $quotation->id]);
        }

        return redirect()->action(ShowQuotationController::class, $quotation);
    }
}
