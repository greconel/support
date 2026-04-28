<span @class(['text-danger' => $invoice->amount < 0])>
    {{ $invoice->amount_formatted }}
</span>
