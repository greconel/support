@forelse($ranges as $range)
    <div
        @class(['mb-5', 'pb-4 border-bottom' => ! $loop->last])
        wire:key="{{ time() . '_charts_' . $loop->index }}"
    >
        <p class="fw-bolder fs-1">{{ $range }}</p>

        <div
            x-data="{
                chart: null,
                labels: {{ json_encode($labels()) }},
                invoiceData: {{ json_encode($invoiceData($range)) }},
                invoiceDataIncl: {{ json_encode($invoiceDataIncl($range)) }},
                expenseDataExcl: {{ json_encode($expenseData($range)) }},
                expenseDataIncl: {{ json_encode($expenseDataInclVat($range)) }},
                expenseTotal: {{ json_encode($expenseDataTotal($range)) }},
                expenseDataVAT: {{ json_encode($expenseDataVAT($range)) }},
                cashInTotal: {{ json_encode($cashInTotalData($range)) }}
            }"
            x-init="
                chart = new Chart(
                    $refs.chart,
                    {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [
                                {
                                    label: '{{ __('Invoices amount excl. VAT') }}',
                                    data: invoiceData,
                                    backgroundColor: 'rgba(53, 204, 58, 0.2)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ __('Invoices amount incl. VAT') }}',
                                    data: invoiceDataIncl,
                                    backgroundColor: 'rgba(53, 204, 58, 0.5)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ __('Cash in total') }}',
                                    data: cashInTotal,
                                    backgroundColor: 'rgba(0, 102, 255, 0.5)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ __('Expenses total out') }}',
                                    data: expenseTotal,
                                    backgroundColor: 'rgba(219, 18, 18, 0.8)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ __('Expenses amount incl. VAT') }}',
                                    data: expenseDataIncl,
                                    backgroundColor: 'rgba(219, 18, 18, 0.6)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ __('Expenses amount excl. VAT') }}',
                                    data: expenseDataExcl,
                                    backgroundColor: 'rgba(219, 18, 18, 0.4)',
                                    borderWidth: 1
                                },
                                {
                                    label: '{{ __('Expenses VAT') }}',
                                    data: expenseDataVAT,
                                    backgroundColor: 'rgba(219, 18, 18, 0.2)',
                                    borderWidth: 1
                                }
                            ]
                        }
                    }
                );
            "
            wire:ignore
        >
            <canvas x-ref="chart"></canvas>
        </div>
    </div>
@empty
    <p class="text-center text-muted mt-4">{{ __('No results for charts') }}</p>
@endforelse
