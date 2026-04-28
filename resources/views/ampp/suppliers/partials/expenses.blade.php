@php
    /** @var \Illuminate\Support\Collection|\App\Models\Expense[] $expenses */
    /** @var \App\Models\Expense $expense */
@endphp

@foreach($expensesGroupedByYear as $year => $expenses)
    <div @class(['mb-5', 'pb-4 border-bottom' => ! $loop->last])>
        <p class="fw-bolder fs-1">
            {{ $year }}
        </p>

        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>{{ __('Number') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Amount excl. VAT') }}</th>
                    <th>{{ __('Amount incl. VAT') }}</th>
                    <th>{{ __('Invoice date') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr class="fw-bolder fs-4 bg-gray-100">
                    <td colspan="2" class="fw-normal">{{ __('Total') }}</td>
                    <td>€ {{ number_format($expenses->sum('amount_excluding_vat'), 2, ',', ' ') }}</td>
                    <td>€ {{ number_format($expenses->sum('amount_including_vat'), 2, ',', ' ') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>

            @foreach($expenses->sortByDesc('invoice_date') as $expense)
                <tr>
                    <td>{{ $expense->number }}</td>
                    <td class="text-{{ $expense->status->color() }}">
                        {{ $expense->status->label() }}
                    </td>
                    <td>
                        {{ $expense->amount_excluding_vat_formatted }}
                    </td>
                    <td>
                        {{ $expense->amount_including_vat_formatted }}
                    </td>
                    <td>{{ $expense->invoice_date?->format('d/m/Y') }}</td>
                    <td>
                        <a
                            href="{{ action(\App\Http\Controllers\Ampp\Expenses\ShowExpenseController::class, $expense) }}"
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
