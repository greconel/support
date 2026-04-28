<?php

namespace App\Http\Controllers\Api\V1\Invoices;

use App\Http\Requests\Api\V1\Invoices\IndexInvoiceRequest;
use App\Http\Resources\InvoiceCollection;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;

class IndexInvoiceController
{
    /**
     * Get all invoices for a client
     *
     * @group Invoices
     * @header Accept application/json
     *
     */
    public function __invoke(IndexInvoiceRequest $request)
    {
        return new InvoiceCollection(
            Invoice::with('billingLines')
                ->where('client_id', '=', $request->input('client_id'))
                ->when($request->has('type'), fn(Builder $q) => $q->where('type', '=', $request->input('type')))
                ->when($request->has('from'), function (Builder $q) use ($request) {
                    return $q
                        ->whereBetween('custom_created_at', [
                            $request->input('from'),
                            $request->input('till')
                        ]);
                })
                ->get()
        );
    }
}
