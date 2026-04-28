@php
    /** @var \App\Models\Invoice $invoice */
@endphp

@forelse($invoicesGrouped as $range => $invoicesGroupedByClientId)
    <div @class(['mb-5', 'pb-4 border-bottom' => ! $loop->last])>
        <p class="fw-bolder fs-1">
            @switch($orderBy)
                @case('year')
                    {{ $range }}
                    @break

                @case('month')
                    {{ \Illuminate\Support\Carbon::createFromFormat('Y/m', $range)->format('F Y') }}
                    @break
            @endswitch
        </p>

        <table class="table table-borderless fs-6">
            <thead>
                <tr>
                    <th>{{ __('Number') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Structured message (OGM)') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Amount incl. VAT') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Created at') }}</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <tr class="fw-bolder fs-4">
                    <td colspan="4" class="fw-normal">{{ __('Total') }}</td>
                    <td>€ {{ number_format($calculateTotal($invoicesGroupedByClientId), 2, ',', ' ') }}</td>
                    <td>€ {{ number_format($calculateTotalWithVat($invoicesGroupedByClientId), 2, ',', ' ') }}</td>
                    <td colspan="3"></td>
                </tr>
            </tbody>

            @php
                $invoicesGroupedByClientId = $invoicesGroupedByClientId->sortBy(function($invoices, $clientId) {
                    return \App\Models\Client::withTrashed()->find($clientId)?->full_name_with_company;
                });
            @endphp

            @foreach($invoicesGroupedByClientId as $clientId => $invoices)
                <tbody>
                    <tr class="bg-gray-100 fw-bolder">
                        <td colspan="4">
                            <a
                                href="{{ action(\App\Http\Controllers\Ampp\Clients\ShowClientController::class, $clientId) }}"
                                target="_blank"
                            >
                                {{ ($getClient($clientId))->full_name_with_company }}
                            </a>
                        </td>

                        <td>€ {{ number_format($calculateTotalForClient($invoices), 2, ',', ' ') }}</td>
                        <td>€ {{ number_format($calculateTotalForClientWithVat($invoices), 2, ',', ' ') }}</td>
                        <td colspan="3"></td>
                    </tr>

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
                            <td>{{ $invoice->invoiceCategory?->name }}</td>
                            <td>{{ $invoice->custom_created_at->format('d/m/Y') }}</td>
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
                </tbody>
            @endforeach
        </table>
    </div>
@empty
    <p class="text-center text-muted mt-4">{{ __('No results for invoices') }}</p>
@endforelse
