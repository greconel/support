@php
    /** @var \App\Models\Expense $expense */
@endphp

@forelse($expensesGrouped as $range => $expensesGroupedBySupplierId)
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

        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>{{ __('Number') }}</th>
                    <th>{{ __('Comment') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Invoice category') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Amount incl. VAT') }}</th>
                    <th>{{ __('Created at') }}</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <tr class="fw-bolder fs-4">
                    <td colspan="5" class="fw-normal">{{ __('Total') }}</td>
                    <td>€ {{ number_format($calculateTotal($expensesGroupedBySupplierId), 2, ',', ' ') }}</td>
                    <td>€ {{ number_format($calculateTotalWithVat($expensesGroupedBySupplierId), 2, ',', ' ') }}</td>
                    <td></td>
                </tr>
            </tbody>

            @foreach($expensesGroupedBySupplierId as $supplierId => $expenses)
                <tbody>
                    <tr class="bg-gray-100">
                        <td colspan="5">
                            <a
                                href="{{ action(\App\Http\Controllers\Ampp\Suppliers\ShowSupplierController::class, $supplierId) }}"
                                target="_blank"
                            >
                                {{ ($getSupplier($supplierId))->company_with_full_name }}
                            </a>
                        </td>

                        <td>€ {{ number_format($calculateTotalForSupplier($expenses), 2, ',', ' ') }}</td>
                        <td>€ {{ number_format($calculateTotalWithVatForSupplier($expenses), 2, ',', ' ') }}</td>
                        <td colspan="2"></td>
                    </tr>

                    @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->number }}</td>
                            <td>
                                <div style="max-width: 200px; overflow-y: auto; max-height: 50px">
                                    {!! $expense->comment !!}
                                </div>
                            </td>
                            <td class="text-{{ $expense->status->color() }}">
                                {{ $expense->status->label() }}
                            </td>
                            <td>
                                {{ $expense->various_transaction_category?->label() }}
                            </td>
                            <td>
                                {{ $expense->invoiceCategory?->name }}
                            </td>
                            <td @class(['text-danger' => $expense->amount_excluding_vat < 0])>
                                {{ $expense->amount_excluding_vat_formatted }}
                            </td>
                            <td @class(['text-danger' => $expense->amount_including_vat < 0])>
                                {{ $expense->amount_including_vat_formatted }}
                            </td>
                            <td>{{ $expense->invoice_date->format('d/m/Y') }}</td>
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
                </tbody>
            @endforeach
        </table>
    </div>
@empty
    <p class="text-center text-muted mt-4">{{ __('No results for expenses') }}</p>
@endforelse

