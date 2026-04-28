@php
    /** @var \Illuminate\Support\Collection|\App\Models\Quotation[] $quotations */
    /** @var \App\Models\Quotation $quotation */
@endphp

@foreach($quotationsGroupedByYear as $year => $quotations)
    <div @class(['mb-5', 'pb-4 border-bottom' => ! $loop->last])>
        <p class="fw-bolder fs-1">
            {{ $year }}
        </p>

        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>{{ __('Number') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Amount incl. VAT') }}</th>
                    <th>{{ __('Created at') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr class="fw-bolder fs-4 bg-gray-100">
                    <td colspan="2" class="fw-normal">{{ __('Total') }}</td>
                    <td>€ {{ number_format($quotations->sum('amount'), 2, ',', ' ') }}</td>
                    <td>€ {{ number_format($quotations->sum('amount_with_vat'), 2, ',', ' ') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>

            @foreach($quotations->sortByDesc('custom_created_at') as $quotation)
                <tr>
                    <td>{{ $quotation->number }}</td>
                    <td class="text-{{ $quotation->status->color() }}">
                        {{ $quotation->status->label() }}
                    </td>
                    <td @class(['text-danger' => $quotation->amount < 0])>
                        {{ $quotation->amount_formatted }}
                    </td>
                    <td @class(['text-danger' => $quotation->amount_with_vat < 0])>
                        {{ $quotation->amount_with_vat_formatted }}
                    </td>
                    <td>{{ $quotation->custom_created_at?->format('d/m/Y') }}</td>
                    <td>
                        <a
                            href="{{ action(\App\Http\Controllers\Ampp\Quotations\ShowQuotationController::class, $quotation) }}"
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
