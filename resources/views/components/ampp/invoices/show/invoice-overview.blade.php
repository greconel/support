<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bolder fs-4">{{ __('Overview') }}</span>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\EditInvoiceLinesController::class, $invoice) }}" class="btn btn-sm rounded btn-outline-secondary">
                <i class="fas fa-edit"></i>
            </a>

            <a href="#invoicePdfPreviewModal" class="btn btn-sm rounded btn-outline-secondary" data-bs-toggle="modal">
                <i class="fas fa-search"></i>
            </a>

            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary rounded-3 dropdown-toggle" type="button" id="invoiceActions" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ __('Actions') }}
                </button>

                <ul class="dropdown-menu" aria-labelledby="invoiceActions">
                    <li>
                        <a class="dropdown-item" href="{{ action(\App\Http\Controllers\Ampp\Invoices\EditInvoiceLinesController::class, $invoice) }}">
                            {{ __('Edit contents') }}
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#invoicePdfPreviewModal" data-bs-toggle="modal">{{ __('Preview') }}</a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ action(\App\Http\Controllers\Ampp\Invoices\DuplicateInvoiceController::class, $invoice) }}">{{ __('Duplicate') }}</a>
                    </li>

                    @if($invoice->type == \App\Enums\InvoiceType::Debit)
                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item" href="#confirmCreateCreditNoteModal" data-bs-toggle="modal">
                                {{ __('Create credit note from this invoice') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <a style="margin-top: -125px; margin-left: -25px;" href="{{ action(\App\Http\Controllers\Ampp\Invoices\CreateInvoiceController::class) }}" class="btn btn-sm rounded btn-success">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>

    <div class="card-body">
        <livewire:ampp.invoices.show.pdf-comment :invoice="$invoice" />

        <table class="table table-borderless table-striped w-100">
            <thead>
                <tr>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Unit price excl. VAT') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('VAT') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($invoice->billingLines as $line)
                    <tr>
                        @if($line->type == 'header')
                            <td colspan="5">{{ $line->text }}</td>

                            @continue
                        @endif

                        <td>
                            {{ $line->text }}

                            @if($line->description)
                                <div class="text-muted small">{!! nl2br($line->description) !!}</div>
                            @endif
                        </td>

                        <td style="white-space: nowrap">
                            {{ $line->price_formatted }}

                            @if($line->discount != 0)
                                <div class="text-muted small">- {{ $line->discount }}%</div>
                            @endif
                        </td>

                        <td style="white-space: nowrap">{{ $line->amount_formatted }}</td>

                        <td style="white-space: nowrap">{{ $line->vat_formatted }}</td>

                        <td style="white-space: nowrap">{{ $line->sub_total_formatted }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            {{ __('Add contents via Actions > Edit contents') }}
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>

        <div class="d-flex flex-column justify-content-end text-end">
            <div>{{ __('Total excl. VAT') }}: {{ $invoice->amount_formatted }}</div>
            <div>{{ __('VAT') }}: {{ $invoice->getVatAmountFormatted() }}</div>
            <div>{{ __('Total') }}: {{ $invoice->amount_with_vat_formatted }}</div>
        </div>

        <hr>

        <p class="text-uppercase lead mb-3">{{ __('Related documents') }}</p>

        @if($invoice->quotation)
            <div class="mb-1">
                {{ __('Quotation') }}:
                <a href="{{ action(\App\Http\Controllers\Ampp\Quotations\ShowQuotationController::class, $invoice->quotation) }}">
                    {{ $invoice->quotation->custom_name }}
                </a>
            </div>

            @foreach($invoice->quotation->invoices->except($invoice->id) as $siblingInvoice)
                <div class="mb-1">
                    @if($siblingInvoice->id == $invoice->parent_invoice_id)
                        {{ __('Invoice (generated from):') }}
                    @else
                        {{ __('Invoice') }}:
                    @endif

                    <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, $siblingInvoice) }}">
                        {{ $siblingInvoice->custom_name }}
                    </a>
                </div>
            @endforeach

            @foreach($invoice->quotation->creditNotes->except($invoice->id) as $creditNote)
                <div class="mb-1">
                    {{ __('Credit note') }}:
                    <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, $creditNote) }}">
                        {{ $creditNote->custom_name }}
                    </a>
                </div>
            @endforeach
        @else
            @foreach($invoice->creditNotes as $creditNote)
                <div class="mb-1">
                    {{ __('Credit note') }}:
                    <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, $creditNote) }}">
                        {{ $creditNote->custom_name }}
                    </a>
                </div>
            @endforeach

            @if($invoice->parentInvoice)
                <div class="mb-1">
                    {{ __('Invoice (generated from):') }}
                    <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, $invoice->parentInvoice) }}">
                        {{ $invoice->parentInvoice->custom_name }}
                    </a>
                </div>
            @endif
        @endif
    </div>
</div>

<x-push name="modals">
    <x-ui.confirmation-modal id="confirmCreateCreditNoteModal">
        <x-slot name="content">
            {{ __('Are you sure you want to create a credit note out of this invoice?') }}
        </x-slot>

        <x-slot name="actions">
            <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\ConvertInvoiceToCreditNoteController::class, $invoice) }}" class="btn btn-primary">
                {{ __('Yes, I\'m sure') }}
            </a>
        </x-slot>
    </x-ui.confirmation-modal>
</x-push>
