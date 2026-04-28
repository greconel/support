<x-layouts.ampp :title="__('Invoices')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All invoices') }}</x-ui.page-title>

        <x-ui.split-button>
            <x-slot name="button">
                <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\CreateInvoiceController::class) }}" class="btn btn-primary">
                    {{ __('Create new invoice') }}
                </a>
            </x-slot>

            <x-slot name="dropdown">
                <li>
                    <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\IndexClearfactsBulkInvoiceController::class) }}" class="dropdown-item">
                        {{ __('Clearfacts bulk upload') }}
                    </a>
                </li>

                <li>
                    <a href="{{ action(\App\Http\Controllers\Ampp\Invoices\ExportInvoicesController::class) }}" class="dropdown-item">
                        {{ __('Export invoices') }}
                    </a>
                </li>
            </x-slot>
        </x-ui.split-button>
    </div>

    <div class="row justify-content-center mb-4">
        <div class="col-md-3">
            <div class="clickable-card" data-invoice-ids="{{ implode(',', $outstandingInvoicesNotExpiredIDs) }}" style="cursor: pointer;">
                <x-ui.info-card max-height="auto">
                    <x-slot name="title">
                        {{ __('Total open amount that hasn\'t been expired yet') }}
                    </x-slot>

                    <x-slot name="icon">
                        <i class="fas fa-euro-sign fa-3x text-red-200"></i>
                    </x-slot>

                    <x-slot name="text">
                        <span class="fs-2 fw-bolder">€ {{ number_format($outstandingInvoicesNotExpiredExclVat, 2, ',', '.') }}</span>
                        <span>{{ __('excl. VAT') }}</span>
                        <br>
                        <span class="fw-bolder">€ {{ number_format($outstandingInvoicesNotExpiredInclVat, 2, ',', '.') }}</span>
                        <span>{{ __('incl. VAT') }}</span>
                    </x-slot>
                </x-ui.info-card>
            </div>
        </div>

        <div class="col-md-3">
            <div class="clickable-card" data-invoice-ids="{{ implode(',', $outstandingInvoicesExpiredForMax30DaysIDs) }}" style="cursor: pointer;">
                <x-ui.info-card max-height="auto">
                    <x-slot name="title">
                        {{ __('Total open amount that is expired for a maximum of 30 days') }}
                    </x-slot>

                    <x-slot name="icon">
                        <i class="fas fa-euro-sign text-yellow-200 fa-3x"></i>
                    </x-slot>

                    <x-slot name="text">
                        <span class="fs-2 fw-bolder">€ {{ number_format($outstandingInvoicesExpiredForMax30DaysExclVat, 2, ',', '.') }}</span>
                        <span>{{ __('excl. VAT') }}</span>
                        <br>
                        <span class="fw-bolder">€ {{ number_format($outstandingInvoicesExpiredForMax30DaysWithVat, 2, ',', '.') }}</span>
                        <span>{{ __('incl. VAT') }}</span>
                    </x-slot>
                </x-ui.info-card>
            </div>
        </div>

        <div class="col-md-3">
            <div class="clickable-card" data-invoice-ids="{{ implode(',', $outstandingInvoicesExpiredLongerThen30DaysIDs) }}" style="cursor: pointer;">
                <x-ui.info-card max-height="auto">
                    <x-slot name="title">
                        {{ __('Total open amount that is expired longer then 30 days') }}
                    </x-slot>

                    <x-slot name="icon">
                        <i class="fas fa-euro-sign text-gray-400 fa-3x"></i>
                    </x-slot>

                    <x-slot name="text">
                        <span class="fs-2 fw-bolder">€ {{ number_format($outstandingInvoicesExpiredLongerThen30DaysExclVat, 2, ',', '.') }}</span>
                        <span>{{ __('excl. VAT') }}</span>
                        <br>
                        <span class="fw-bolder">€ {{ number_format($outstandingInvoicesExpiredLongerThen30DaysInclVat, 2, ',', '.') }}</span>
                        <span>{{ __('incl. VAT') }}</span>
                    </x-slot>
                </x-ui.info-card>
            </div>
        </div>

        <div class="col-md-3">
            <div class="clickable-card" data-invoice-ids="{{ implode(',', array_unique(array_merge($outstandingInvoicesNotExpiredIDs, $outstandingInvoicesExpiredForMax30DaysIDs, $outstandingInvoicesExpiredLongerThen30DaysIDs))) }}" style="cursor: pointer;">
                <x-ui.info-card max-height="auto">
                    <x-slot name="title">
                        {{ __('Total open amount') }}
                    </x-slot>

                    <x-slot name="icon">
                        <i class="fas fa-euro-sign text-blue-400 fa-3x"></i>
                    </x-slot>

                    <x-slot name="text">
                        <span class="fs-2 fw-bolder">€ {{ number_format($outstandingInvoicesExpiredLongerThen30DaysExclVat + $outstandingInvoicesNotExpiredExclVat + $outstandingInvoicesExpiredForMax30DaysExclVat, 2, ',', '.') }}</span>
                        <span>{{ __('excl. VAT') }}</span>
                        <br>
                        <span class="fw-bolder">€ {{ number_format($outstandingInvoicesExpiredLongerThen30DaysInclVat + $outstandingInvoicesNotExpiredInclVat + $outstandingInvoicesExpiredForMax30DaysWithVat, 2, ',', '.') }}</span>
                        <span>{{ __('incl. VAT') }}</span>
                    </x-slot>
                </x-ui.info-card>
            </div>
        </div>


    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="invoice-table" :placeholder="__('Search invoices...')" />

            <div class="dataTable-filters">
                @foreach(\App\Enums\InvoiceType::cases() as $type)
                    @php
                        $query = \App\Models\Invoice::where('type', '=', $type);

                        if (request()->input('invoiceids')) {
                            $invoiceIds = explode(',', request()->input('invoiceids'));
                            $query->whereIn('id', $invoiceIds);
                        }

                        $count = $query->count();
                    @endphp

                    <x-table.filters-option state="type" value="{{ $type->value }}" :count="$count">
                        {{ $type->label()  }}
                    </x-table.filters-option>
                @endforeach

                @foreach(\App\Enums\InvoiceStatus::cases() as $status)
                    @php
                        $query = \App\Models\Invoice::where('status', '=', $status);

                        if (request()->input('type')){
                            $query->where('type', '=', request()->input('type'));
                        }

                        if (request()->input('invoiceids')) {
                            $invoiceIds = explode(',', request()->input('invoiceids'));
                            $query->whereIn('id', $invoiceIds);
                        }

                        $count = $query->count();
                    @endphp

                    <x-table.filters-option state="status" value="{{ $status->value }}" :count="$count">
                        {{ $status->label()  }}
                    </x-table.filters-option>
                @endforeach
            </div>
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="invoice-table" :redirect="action(\App\Http\Controllers\Ampp\Invoices\ShowInvoiceController::class, ':id')" />

        <script>
            $(document).ready(function() {
                $('.clickable-card').on('click', function() {
                    const invoiceIds = $(this).data('invoice-ids');
                    const url = new URL(window.location);
                    const currentInvoiceIds = url.searchParams.get('invoiceids');

                    if (invoiceIds) {
                        if (currentInvoiceIds == invoiceIds) {
                            url.searchParams.delete('invoiceids');
                        } else {
                            url.searchParams.set('invoiceids', invoiceIds);

                            url.searchParams.delete('status');
                            url.searchParams.delete('type');
                        }

                        window.location.href = url.toString();
                    }
                });

                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('invoiceids')) {
                    const activeInvoiceIds = urlParams.get('invoiceids');
                    $('.clickable-card').each(function() {
                        if ($(this).data('invoice-ids') == activeInvoiceIds) {
                            $(this).find('.card').addClass('border-primary border-3');
                        }
                    });
                }
            });
        </script>
    </x-push>
</x-layouts.ampp>
