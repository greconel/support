<span @class(['text-danger' => $invoice->amount_with_vat < 0])>
    {{ $invoice->amount_with_vat_formatted }}
</span>
@if($invoice->is_partially_paid)
    <br>
    <small class="text-warning">{{ __('Open') }}: {{ $invoice->remaining_balance_formatted }}</small>
@endif
