<x-layouts.ampp :title="__('Edit invoice category')">
    <div class="card container">
        <div class="card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\InvoiceCategories\UpdateInvoiceCategoryController::class, $invoiceCategory) }}" method="patch">
                <div class="row">
                    <div class="col-12 mb-4">
                        <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                        <x-forms.input name="name" :value="$invoiceCategory->name" required />
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="#confirmDeleteModal" data-bs-toggle="modal" class="link-secondary">{{ __('Delete category') }}</a>

                    <x-forms.submit>{{ __('Edit category') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>

    <x-push name="modals">
        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this invoice category?') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\InvoiceCategories\DestroyInvoiceCategoryController::class, $invoiceCategory)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
