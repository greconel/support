<div class="position-relative">
    <div wire:loading>
        <div
            class="position-absolute start-0 top-0 bg-gray-500 bg-opacity-25 d-flex justify-content-center align-items-center rounded"
            style="width: 100%; height: 100%; z-index: 2; backdrop-filter: blur(5px)"
        >
            <div class="spinner-grow" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <section class="mb-4">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-body" style="max-height: 250px; overflow-y: auto">
                    <div class="d-flex gap-3">
                        <i class="fas fa-euro-sign text-green-200 fa-3x"></i>

                        <div class="d-flex flex-column">
                            <span class="text-gray-500">{{ __('Total amount invoices') }}</span>

                            <span class="fs-2 fw-bolder">
                                € {{ number_format($totalInvoiceAmount, '2', ',', ' ') }}
                                <span class="fs-6 fw-normal">{{ __('excl. VAT') }}</span>
                            </span>

                            <span class="fs-4 fw-bolder">
                                € {{ number_format($totalInvoiceAmountWithVat, '2', ',', ' ') }}
                                <span class="fs-6 fw-normal">{{ __('incl. VAT') }}</span>
                            </span>

                            <span class="fs-4 fw-bolder">
                                € {{ number_format($averageMonthlyInvoicing, '2', ',', ' ') }}
                                <span class="fs-6 fw-normal">{{ __('Average monthly invoicing') }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-body" style="max-height: 250px; overflow-y: auto">
                    <div class="d-flex gap-3">
                        <i class="fas fa-hand-holding-usd text-red-200 fa-3x"></i>

                        <div class="d-flex flex-column">
                            <span class="text-gray-500">{{ __('Total amount expenses') }}</span>

                            <span class="fs-2 fw-bolder">
                                € {{ number_format($totalExpenseAmount, '2', ',', ' ') }}
                                <span class="fs-6 fw-normal">{{ __('excl. VAT') }}</span>
                            </span>

                            <span class="fs-4 fw-bolder">
                                € {{ number_format($totalExpenseAmountWithVat, '2', ',', ' ') }}
                                <span class="fs-6 fw-normal">{{ __('incl. VAT') }}</span>
                            </span>

                            <span class="fs-4 fw-bolder">
                                € {{ number_format($totalExpenseVATCategory, '2', ',', ' ') }}
                                <span class="fs-6 fw-normal">{{ __('VAT cat.') }}</span>
                            </span>
                            <span class="fs-4 fw-bolder">
                                € {{ number_format($totalExpenseAmountIncl, '2', ',', ' ') }}
                                <span class="fs-6 fw-normal">{{ __('Total out') }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-body" style="max-height: 250px; overflow-y: auto">
                    <div class="d-flex gap-3">
                        <i class="fas fa-user text-yellow-200 fa-3x"></i>

                        <div class="d-flex flex-column">
                            <span class="text-gray-500">{{ __('Topmost client') }}</span>

                            @if($topMostClient)
                                <span class="fs-5 fw-bolder">
                                    {{ $this->topMostClient->full_name_with_company }}
                                </span>

                                <span class="fs-2 fw-bolder">
                                    € {{ number_format($topMostClientAmount, '2', ',', ' ') }}
                                    <span class="fs-6 fw-normal">{{ __('excl. VAT') }}</span>
                                </span>
                            @else
                                /
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-body" style="max-height: 250px; overflow-y: auto">
                    <div class="d-flex gap-3">
                        <i class="fas fa-calculator text-blue-300 fa-3x"></i>

                        <div class="d-flex flex-column">
                            <span class="text-gray-500">{{ __('Operational result') }}</span>

                            <span class="fs-2 fw-bolder">
                                € {{ number_format($operationalResult, '2', ',', ' ') }}
                                <span class="fs-6 fw-normal">{{ __('excl. VAT') }}</span>
                            </span>

                            <span class="fs-4 fw-bolder">
                                {{ number_format($operationalMargin, '2', ',', ' ') }} %
                                <span class="fs-6 fw-normal">{{ __('operational margin') }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-body" style="max-height: 250px; overflow-y: auto">
                    <div class="d-flex gap-3">
                        <i class="fas fa-calculator text-blue-300 fa-3x"></i>

                        <div class="d-flex flex-column">
                            <span class="text-gray-500">{{ __('Cash flow') }}</span>

                            <span class="fs-2 fw-bolder">
                                € {{ number_format($cashFlow, '2', ',', ' ') }}
                            </span>

                            <span class="fs-4 fw-bolder">
                                {{ number_format($cashFlowMargin, '2', ',', ' ') }} %
                                <span class="fs-6 fw-normal">{{ __('cash flow margin') }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-body" style="max-height: 250px; overflow-y: auto">
                    <div class="d-flex gap-3">
                        <i class="fas fa-truck-loading text-red-400 fa-3x"></i>

                        <div class="d-flex flex-column">
                            <span class="text-gray-500">{{ __('Topmost supplier') }}</span>

                            @if($topMostSupplier)
                                <span class="fs-5 fw-bolder">
                                    {{ $this->topMostSupplier->company_with_full_name }}
                                </span>

                                <span class="fs-2 fw-bolder">
                                    € {{ number_format($topMostSupplierAmount, '2', ',', ' ') }}
                                    <span class="fs-6 fw-normal">{{ __('excl. VAT') }}</span>
                                </span>
                            @else
                                /
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <nav class="nav justify-content-end mb-4">
            <a
                @class(['nav-link', 'link-secondary' => $orderBy != 'year'])
                href="#"
                wire:click.prevent="changeOrderBy('year')"
            >
                {{ __('Per year') }}
            </a>

            <a
                @class(['nav-link', 'link-secondary' => $orderBy != 'month'])
                href="#"
                wire:click.prevent="changeOrderBy('month')"
            >
                {{ __('Per month') }}
            </a>
        </nav>

        <nav>
            <div class="nav nav-tabs gap-3" id="nav-tab" role="tablist">
                <button class="nav-link active position-relative" data-bs-toggle="tab" data-bs-target="#invoices" type="button">
                    {{ __('Invoices') }}
                </button>

                <button class="nav-link position-relative" data-bs-toggle="tab" data-bs-target="#expenses" type="button">
                    {{ __('Expenses') }}
                </button>

                <button @class(['nav-link position-relative', 'disabled' => $orderBy !== 'year']) data-bs-toggle="tab" data-bs-target="#charts" type="button">
                    {{ __('Charts') }}
                </button>
            </div>
        </nav>

        <div class="tab-content p-3" id="nav-tabContent">
            <div class="tab-pane fade show active" id="invoices">
                <div class="table-responsive">
                    <x-ampp.billing-reports.invoice-overview :invoices-grouped="$invoices->sortKeysDesc()" :order-by="$orderBy" />
                </div>
            </div>

            <div class="tab-pane fade" id="expenses">
                <div class="table-responsive">
                    <x-ampp.billing-reports.expense-overview :expenses-grouped="$expenses->sortKeysDesc()" :order-by="$orderBy" />
                </div>
            </div>

            <div class="tab-pane fade" id="charts">
               @if($orderBy == 'year')
                    <x-ampp.billing-reports.chart-overview
                        :invoices-grouped="$invoices->sortKeysDesc()"
                        :expenses-grouped="$expenses->sortKeysDesc()"
                        :cash-in-total="$cashInTotal->sortKeysDesc()"
                        :expenses-vat-grouped="$expensesVAT->sortKeysDesc()"
                        :expenses-total="$expensesTotal->sortKeysDesc()"
                        :order-by="$orderBy"
                    />
                @else
                   <p class="text-center text-muted mt-4">
                       {{ __('Charts are only available when ordered by year.') }}
                   </p>
                @endif
            </div>
        </div>
    </section>
</div>

