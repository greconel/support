<x-layouts.ampp :title="__('Edit product')" :breadcrumbs="Breadcrumbs::render('editProduct', $product)">
    <div class="container">
        <x-ui.page-title>{{ __('Edit product') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Products\UpdateProductController::class, $product) }}" method="patch">
                <div class="row">
                    <div class="col-md-10 mb-3">
                        <x-forms.label for="name">{{ __('Name') }}*</x-forms.label>
                        <x-forms.input name="name" required :value="$product->name" />
                    </div>

                    <div class="col-md-2 mb-3">
                        <x-forms.label for="price">{{ __('Price') }}*</x-forms.label>
                        <x-forms.input type="number" name="price" step=".01" min="0" required :value="$product->price" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-forms.label for="description">{{ __('Description') }}</x-forms.label>
                    <x-forms.quill name="description" :value="$product->description" />
                </div>

                <livewire:images :model="$product" />

                <div class="d-flex justify-content-between">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" class="btn btn-link link-secondary">
                        {{ __('Delete product') }}
                    </button>

                    <x-forms.submit>{{ __('Edit product') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>

    <x-push name="modals">
        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this product? This action can not be reverted!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\Products\DestroyProductController::class, $product)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
