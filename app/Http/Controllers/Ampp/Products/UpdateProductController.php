<?php

namespace App\Http\Controllers\Ampp\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Products\UpdateProductRequest;
use App\Models\BillingLines;
use App\Models\Product;
use App\Models\RecurringInvoice;

class UpdateProductController extends Controller
{
    public function __invoke(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $oldPrice = $product->price;

        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
        ]);

        // Sync price on recurring invoice lines that reference this product
        if ($oldPrice != $product->price) {
            $recurringInvoiceIds = BillingLines::where('product_id', $product->id)
                ->where('model_type', RecurringInvoice::class)
                ->pluck('model_id')
                ->unique();

            BillingLines::where('product_id', $product->id)
                ->where('model_type', RecurringInvoice::class)
                ->each(function (BillingLines $line) use ($product) {
                    $subtotal = $product->price * $line->amount;
                    $subtotal -= $subtotal * (($line->discount ?? 0) / 100);

                    $line->update([
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);
                });

            // Recalculate totals on affected recurring invoices
            foreach ($recurringInvoiceIds as $id) {
                $ri = RecurringInvoice::find($id);
                if ($ri) {
                    $amount = 0;
                    $amountWithVat = 0;
                    foreach ($ri->billingLines as $line) {
                        if ($line->type === 'text' && $line->subtotal) {
                            $amount += $line->subtotal;
                            $amountWithVat += $line->subtotal * (1 + ($line->vat / 100));
                        }
                    }
                    $ri->update(['amount' => $amount, 'amount_with_vat' => $amountWithVat]);
                }
            }
        }

        if ($request->has('images')) {
            foreach ($request->input('images') as $image) {
                $product
                    ->addMedia(storage_path('app/livewire-tmp/' . $image['path']))
                    ->usingName($image['name'])
                    ->toMediaCollection('images', 'private')
                ;
            }
        }

        return redirect()
            ->action(IndexProductController::class)
            ->with('success', __('Successfully edited product'))
        ;
    }
}
