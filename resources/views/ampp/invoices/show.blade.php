<x-layouts.ampp :title="__('Invoice :name', ['name' => $invoice->custom_name])" :breadcrumbs="Breadcrumbs::render('showInvoice', $invoice)">
    <div class="container">
        <x-ui.page-title>
            {{ __('Invoice :name', ['name' => $invoice->custom_name]) }}

            @if($invoice->type == \App\Enums\InvoiceType::Credit)
                ({{ __('credit note') }})
            @endif
        </x-ui.page-title>

        <div class="row justify-content-center">
            <div class="col-lg-5">
                {{-- Details --}}
                <livewire:ampp.invoices.show.details :invoice="$invoice" />
                
                {{-- Payments (only for Debit invoices that are not Draft) --}}
                @if($invoice->type == \App\Enums\InvoiceType::Debit && $invoice->status != \App\Enums\InvoiceStatus::Draft)
                    <livewire:ampp.invoices.show.payments :invoice="$invoice" />
                @endif

                {{-- Notes --}}
                <livewire:ampp.invoices.show.notes :invoice="$invoice" />

                {{-- CLearfacts notes --}}
                <livewire:ampp.invoices.show.clearfacts-comment :invoice="$invoice" />
            </div>

            <div class="col-lg-7">
                @if($invoice->client?->invoice_note)
                    <div class="alert alert-warning" style="margin: 6px 60px 6px 6px" role="alert">
                        {{ $invoice->client->invoice_note }}
                    </div>
                @endif
                {{-- Invoice lines --}}
                <x-ampp.invoices.show.invoice-overview :invoice="$invoice" />

                {{-- Peppol --}}
                <livewire:ampp.invoices.show.peppol :invoice="$invoice" />

                {{-- Emails --}}
                <div class="card">
                    <div class="card-header">
                        {{ __('E-mails') }}
                    </div>

                    <div class="card-body">
                        <livewire:ampp.emails.list-for-model :email-model="$invoice" create-modal="invoiceEmailModal" />
                    </div>
                </div>

                {{-- Files --}}
                <livewire:ampp.invoices.show.files :invoice="$invoice" />
            </div>
        </div>
    </div>

    <x-push name="modals">
        <livewire:ampp.invoices.show.edit-modal :invoice="$invoice" />
        <livewire:ampp.invoices.show.note-modal :invoice="$invoice" />
        @if($invoice->type == \App\Enums\InvoiceType::Debit && $invoice->status != \App\Enums\InvoiceStatus::Draft)
            <livewire:ampp.invoices.show.payment-modal :invoice="$invoice" />
        @endif
        <livewire:ampp.invoices.show.clearfacts-comment-modal :invoice="$invoice" />
        <livewire:ampp.invoices.show.pdf-comment-modal :invoice="$invoice" />
        <livewire:ampp.invoices.show.pdf-preview :invoice="$invoice" />
        <livewire:ampp.invoices.show.email-modal :invoice="$invoice" />
        <livewire:ampp.emails.preview-modal :email-model="$invoice" />
        <livewire:ampp.media.preview-modal />
    </x-push>
</x-layouts.ampp>
