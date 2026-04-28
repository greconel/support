<x-layouts.ampp :title="__('Recurring invoices')" :breadcrumbs="Breadcrumbs::render('indexRecurringInvoice')">
    <div class="page-header">
        <x-ui.page-title>{{ __('Recurring invoices') }}</x-ui.page-title>

        <a href="{{ action(\App\Http\Controllers\Ampp\RecurringInvoices\CreateRecurringInvoiceController::class) }}" class="btn btn-primary">
            {{ __('Create recurring invoice') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="recurring-invoice-table" :placeholder="__('Search recurring invoices...')" />

            <div class="dataTable-filters">
                @foreach(\App\Enums\RecurringInvoicePeriod::cases() as $period)
                    <x-table.filters-option state="period" value="{{ $period->value }}" :count="\App\Models\RecurringInvoice::where('period', $period)->count()">
                        {{ $period->label() }}
                    </x-table.filters-option>
                @endforeach
            </div>
        </div>

        {{ $dataTable->table() }}

        <div class="mt-3">
            <button type="button" class="btn btn-success" id="generate-invoices-btn" disabled>
                <i class="fas fa-file-invoice"></i> {{ __('Generate invoices') }}
            </button>
        </div>
    </div>

    {{-- Generate invoices modal --}}
    <div class="modal fade" id="generateInvoicesModal" tabindex="-1" aria-labelledby="generateInvoicesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ action(\App\Http\Controllers\Ampp\RecurringInvoices\GenerateInvoicesController::class) }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="generateInvoicesModalLabel">{{ __('Generate invoices') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted mb-3">
                            {{ __('Selected recurring invoices:') }} <strong id="selected-count">0</strong>
                        </p>

                        <div id="selected-ids-container"></div>

                        <div class="mb-3">
                            <label for="invoice_date" class="form-label">{{ __('Invoice date') }}</label>
                            <input type="text" class="form-control" name="invoice_date" id="invoiceDate" required>
                        </div>

                        <div class="mb-3">
                            <label for="expiration_date" class="form-label">{{ __('Expiration date') }}</label>
                            <input type="text" class="form-control" name="expiration_date" id="expirationDate" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Generate as draft') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="recurring-invoice-table" :redirect="action(\App\Http\Controllers\Ampp\RecurringInvoices\ShowRecurringInvoiceController::class, ':id')" />

        <script>
            $(document).ready(function() {
                const generateModal = new bootstrap.Modal(document.getElementById('generateInvoicesModal'));

                // Select all checkbox
                $(document).on('change', '#select-all-recurring', function() {
                    $('.recurring-invoice-checkbox').prop('checked', $(this).is(':checked'));
                    updateGenerateButton();
                });

                // Individual checkbox
                $(document).on('change', '.recurring-invoice-checkbox', function() {
                    updateGenerateButton();
                });

                function updateGenerateButton() {
                    const checked = $('.recurring-invoice-checkbox:checked').length;
                    $('#generate-invoices-btn').prop('disabled', checked === 0);
                }

                // Generate button click
                $('#generate-invoices-btn').on('click', function() {
                    const selectedIds = [];
                    $('.recurring-invoice-checkbox:checked').each(function() {
                        selectedIds.push($(this).val());
                    });

                    $('#selected-count').text(selectedIds.length);
                    $('#selected-ids-container').empty();
                    selectedIds.forEach(function(id) {
                        $('#selected-ids-container').append(
                            '<input type="hidden" name="recurring_invoice_ids[]" value="' + id + '">'
                        );
                    });

                    generateModal.show();
                });

                // Init date pickers when modal is shown
                document.getElementById('generateInvoicesModal').addEventListener('shown.bs.modal', function () {
                    if (!$('#invoiceDate').data('flatpickr')) {
                        flatpickr('#invoiceDate', {
                            defaultDate: new Date(),
                            dateFormat: 'd/m/Y'
                        });

                        flatpickr('#expirationDate', {
                            defaultDate: moment().add(30, 'days').toDate(),
                            dateFormat: 'd/m/Y'
                        });
                    }
                });

                // Re-bind checkboxes after DataTable redraws
                $('#recurring-invoice-table').on('draw.dt', function() {
                    updateGenerateButton();
                });
            });
        </script>
    </x-push>
</x-layouts.ampp>
