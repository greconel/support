<x-layouts.ampp :title="__('Expenses Clearfacts bulk upload')" :breadcrumbs="Breadcrumbs::render('SelectExpensesForClearfacts')">
    <div class="page-header">
        <div>
            <x-ui.page-title>{{ __('Clearfacts bulk upload for expenses') }}</x-ui.page-title>

            <p class="text-gray-600">
                {!! __('Below you\'ll see a list of all expenses not yet uploaded to Clearfacts. Select all expenses you want to send to Clearfacts and click "Upload to Clearfacts". We will queue your request and upload every expense in the background while you can keep working on something else. When all expenses are done uploading to Clearfacts you will receive a notification via mail with an overview, including possible failed uploads. <b>Expenses marked in red do not have a PDF and will not be uploaded to Clearfacts.</b>') !!}
            </p>
        </div>
    </div>

    <x-forms.form :action="action(\App\Http\Controllers\Ampp\Expenses\UploadClearfactsBulkExpenseController::class)">
        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-primary">{{ __('Upload to Clearfacts') }}</button>
        </div>

        <table class="table table-borderless table-hover">
            <thead>
                <tr>
                    <th>
                        <x-forms.checkbox name="check_all" id="checkAll" />
                    </th>
                    <th>{{ __('Number') }}</th>
                    <th>{{ __('Supplier') }}</th>
                    <th>{{ __('Company') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Amount excl. VAT') }}</th>
                    <th>{{ __('Amount incl. VAT') }}</th>
                    <th>{{ __('Invoice date') }}</th>
                    <th>{{ __('Comment (for Clearfacts)') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($expenses as $expense)
                    <tr @class(['cursor-pointer', 'bg-red-50' => ! $expense->getFirstMedia()])>
                        <td>
                            <x-forms.checkbox name="expenses[]" :value="$expense->id" x-ref="checkbox" />
                        </td>

                        <td>{{ $expense->number }}</td>

                        <td>{{ $expense->supplier->full_name }}</td>

                        <td>{{ $expense->supplier->company }}</td>

                        <td class="text-{{ $expense->status->color() }}">{{ $expense->status->label() }}</td>

                        <td>{{ $expense->amount_excluding_vat_formatted }}</td>

                        <td>{{ $expense->amount_including_vat_formatted }}</td>

                        <td>{{ $expense->invoice_date->format('d/m/Y') }}</td>

                        <td style="width: 15%;">
                            <div style="max-height: 50px; overflow-y: auto">
                                {{ $expense->clearfacts_comment }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-forms.form>

    <x-push name="scripts">
        <script>
            /**
             * Table select all
             */

            const checkAllCheckbox = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('table input[type=checkbox]');

            checkAllCheckbox.addEventListener('change', function (e) {
                _.forEach(checkboxes, function (checkbox) {
                    $(checkbox).attr('checked', e.currentTarget.checked);
                });
            });

            /**
             * Table shift select
             */

            const tableRows = $('table tbody tr');
            const tableCheckboxes = $('table tbody input[type=checkbox]')
            let lastChecked = null;

            tableRows.click(function (e){
                document.getSelection().removeAllRanges();

                const tableRowCheckbox = $(this).find('input[type=checkbox]');

                tableRowCheckbox.attr('checked', ! tableRowCheckbox.attr('checked'));

                if (! lastChecked){
                    lastChecked = tableRowCheckbox;
                    return;
                }

                if(e.shiftKey) {
                    const start = tableCheckboxes.index(tableRowCheckbox);
                    const end = tableCheckboxes.index(lastChecked);

                    tableCheckboxes
                        .slice(Math.min(start, end), Math.max(start, end) + 1)
                        .attr('checked', lastChecked.attr('checked'));
                }

                lastChecked = tableRowCheckbox;
            });
        </script>
    </x-push>
</x-layouts.ampp>
