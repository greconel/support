<x-layouts.ampp :title="__('Create new invoice category')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new invoice category') }}</x-ui.page-title>

        <div class="card">
            <div class="card-body">
                <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\InvoiceCategories\StoreInvoiceCategoryController::class) }}"
                              method="post">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                            <x-forms.input name="name" required/>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <x-forms.submit>{{ __('Create new category') }}</x-forms.submit>
                    </div>
                </x-forms.form>
            </div>
        </div>
    </div>
</x-layouts.ampp>
