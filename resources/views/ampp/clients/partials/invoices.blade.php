@php
    /** @var \Illuminate\Support\Collection|\App\Models\Invoice[] $invoices */
    /** @var \App\Models\Invoice $invoice */
@endphp

@foreach($invoicesGroupedByYear as $year => $invoices)
    <div @class(['mb-5', 'pb-4 border-bottom' => ! $loop->last])>
        <p class="fw-bolder fs-1">
            {{ $year }}
        </p>

        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>{{ __('Number') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Structured message (OGM)') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Amount incl. VAT') }}</th>
                    <th>{{ __('Created at') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr class="fw-bolder fs-4 bg-gray-100">
                    <td colspan="4" class="fw-normal">{{ __('Total') }}</td>
                    <td>€ {{ number_format($invoices->sum('amount'), 2, ',', ' ') }}</td>
                    <td>€ {{ number_format($invoices->sum('amount_with_vat'), 2, ',', ' ') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>

            @foreach($invoices->sortByDesc('custom_created_at') as $invoice)
                <tr>
                    <td>{{ $invoice->number }}</td>
                    <td>{{ $invoice->type->label() }}</td>
                    <td>{{ $invoice->ogm }}</td>
                    <td class="text-{{ $invoice->status->color() }}">
                        {{ $invoice->status->label() }}
                    </td>
                    <td @class(['text-danger' => $invoice->amount < 0])>
                        {{ $invoice->amount_formatted }}
                    </td>
                    <td @class(['text-danger' => $invoice->amount_with_vat < 0])>
                        {{ $invoice->amount_with_vat_formatted }}
                    </td>
                    <td>{{ $invoice->custom_created_at?->format('d/m/Y') }}</td>
                    <td>
                        <a
                            href="{{ action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, $invoice) }}"
                            target="_blank"
                        >
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endforeach
