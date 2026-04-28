<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bolder fs-4">{{ __('Overview') }}</span>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ action(\App\Http\Controllers\Ampp\Quotations\EditQuotationLinesController::class, $quotation) }}" class="btn btn-sm rounded btn-outline-secondary">
                <i class="fas fa-edit"></i>
            </a>

            <a href="#quotationPdfPreviewModal" class="btn btn-sm rounded btn-outline-secondary" data-bs-toggle="modal">
                <i class="fas fa-search"></i>
            </a>

            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary rounded-3 dropdown-toggle" type="button" id="quotationActions" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ __('Actions') }}
                </button>

                <ul class="dropdown-menu" aria-labelledby="quotationActions">
                    <li>
                        <a class="dropdown-item" href="#quotationPdfPreviewModal" data-bs-toggle="modal">
                            {{ __('Preview') }}
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ action(\App\Http\Controllers\Ampp\Quotations\EditQuotationLinesController::class, $quotation) }}">
                            {{ __('Edit contents') }}
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item" href="#confirmCreateInvoiceModal" data-bs-toggle="modal">
                            {{ __('Create invoice from this quotation') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card-body">
        <livewire:ampp.quotations.show.pdf-comment :quotation="$quotation" />

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
                @forelse($quotation->billingLines as $line)
                    <tr>
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
                        <td colspan="5" class="text-center text-muted">{{ __('Add contents via Actions > Edit contents') }}</td>
                    </tr>
                @endforelse

            </tbody>
        </table>

        <div class="d-flex flex-column justify-content-end text-end mb-4">
            <div>{{ __('Total excl. VAT') }}: {{ $quotation->amount_formatted }}</div>
            <div>{{ __('VAT') }}: {{ $quotation->getVatAmountFormatted() }}</div>
            <div>{{ __('Total') }}: {{ $quotation->amount_with_vat_formatted }}</div>
        </div>

        <hr>

        <p class="text-uppercase lead mb-3">{{ __('Related documents') }}</p>

        @foreach($quotation->invoices as $invoice)
            <div class="mb-1">
                {{ __('Invoice') }}:
                <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, $invoice) }}">
                    {{ $invoice->custom_name }}
                </a>
            </div>
        @endforeach

        @foreach($quotation->creditNotes as $creditNote)
            <div class="mb-1">
                {{ __('Credit note') }}:
                <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, $creditNote) }}">
                    {{ $creditNote->custom_name }}
                </a>
            </div>
        @endforeach
    </div>
</div>

<x-push name="modals">
    <x-ui.confirmation-modal id="confirmCreateInvoiceModal">
        <x-slot name="content">
            {{ __('Are you sure you want to create an invoice out of this quotation?') }}
        </x-slot>

        <x-slot name="actions">
            <a href="{{ action(\App\Http\Controllers\Ampp\Quotations\ConvertQuotationToInvoiceController::class, $quotation) }}" class="btn btn-primary">
                {{ __('Yes, I\'m sure') }}
            </a>
        </x-slot>
    </x-ui.confirmation-modal>
</x-push>
