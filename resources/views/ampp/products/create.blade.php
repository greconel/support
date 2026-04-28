<x-layouts.ampp :title="__('Create new product')" :breadcrumbs="Breadcrumbs::render('createProduct')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new product') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Products\StoreProductController::class) }}">
                <div class="row">
                    <div class="col-md-10 mb-3">
                        <x-forms.label for="name">{{ __('Name') }}*</x-forms.label>
                        <x-forms.input name="name" required />
                    </div>

                    <div class="col-md-2 mb-3">
                        <x-forms.label for="price">{{ __('Price') }}*</x-forms.label>
                        <x-forms.input type="number" name="price" step=".01" min="0" required />
                    </div>
                </div>

                <div class="mb-3">
                    <x-forms.label for="description">{{ __('Description') }}</x-forms.label>
                    <x-forms.quill name="description" />
                </div>

                <livewire:images :model="new \App\Models\Product()" />

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create new product') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
