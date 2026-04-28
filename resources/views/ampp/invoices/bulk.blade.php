<x-layouts.ampp :title="__('Invoices Clearfacts bulk upload')" :breadcrumbs="Breadcrumbs::render('SelectInvoicesForClearfacts')">
    <div class="page-header">
        <div>
            <x-ui.page-title>{{ __('Clearfacts bulk upload for invoices') }}</x-ui.page-title>

            <p class="text-gray-600">
                {{ __('Below you\'ll see a list of all invoices not yet uploaded to Clearfacts. Select all invoices you want to send to Clearfacts and click "Upload to Clearfacts". We will queue your request and upload every invoice in the background while you can keep working on something else. When all invoices are done uploading to Clearfacts you will receive a notification via mail with an overview, including possible failed uploads.') }}
            </p>
        </div>
    </div>

    <x-forms.form :action="action(\App\Http\Controllers\Ampp\Invoices\UploadClearfactsBulkInvoiceController::class)">
        <div class="d-flex justify-content-end mb-4">
            <x-ui.split-button>
                <x-slot name="button">
                    <button type="submit" class="btn btn-primary">{{ __('Upload to Clearfacts') }}</button>
                </x-slot>

                <x-slot name="dropdown">
                    <li>
                        <button
                            type="submit"
                            formaction="{{ action(\App\Http\Controllers\Ampp\Invoices\BulkDisableClearfactsController::class) }}"
                            class="dropdown-item"
                        >
                            {{ __('Disable for Clearfacts bulk') }}
                        </button>
                    </li>
                </x-slot>
            </x-ui.split-button>
        </div>

        <table class="table table-borderless table-hover">
            <thead>
                <tr>
                    <th>
                        <x-forms.checkbox name="check_all" id="checkAll" />
                    </th>
                    <th>{{ __('Number') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Structured message (OGM)') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Amount incl. VAT') }}</th>
                    <th>{{ __('Created at') }}</th>
                    <th>{{ __('Comment (for Clearfacts)') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($invoices as $invoice)
                    <tr class="cursor-pointer">
                        <td>
                            <x-forms.checkbox name="invoices[]" :value="$invoice->id" />
                        </td>

                        <td>{{ $invoice->number }}</td>

                        <td>{{ $invoice->type->label() }}</td>

                        <td>{{ $invoice->ogm }}</td>

                        <td class="text-{{ $invoice->status->color() }}">{{ $invoice->status->label() }}</td>

                        <td @class(['text-danger' => $invoice->amount < 0])>{{ $invoice->amount_formatted }}</td>

                        <td @class(['text-danger' => $invoice->amount_with_vat < 0])>{{ $invoice->amount_with_vat_formatted }}</td>

                        <td>{{ $invoice->custom_created_at->format('d/m/Y') }}</td>

                        <td style="width: 15%;">
                            <div style="max-height: 50px; overflow-y: auto">
                                {{ $invoice->clearfacts_comment }}
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
