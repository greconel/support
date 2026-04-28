<x-layouts.ampp :title="__('Edit invoice contents')" :breadcrumbs="Breadcrumbs::render('editInvoiceLines', $invoice)">
    <div class="container-fluid">
        @if($invoice->client?->invoice_note)
            <div class="alert alert-warning" role="alert">
                {{ $invoice->client->invoice_note }}
            </div>
        @endif

        @if($invoice->type == \App\Enums\InvoiceType::Credit)
            <div class="alert alert-warning" role="alert">
                {{ __('Attention, when creating a credit note, put a minus (-) sign in front of your price. This is a global credit note requirement.') }}
            </div>
        @endif

        <livewire:ampp.invoices.lines :invoice="$invoice" :session-key="'invoice_' . $invoice->id . '_lines'" />
    </div>
</x-layouts.ampp>
