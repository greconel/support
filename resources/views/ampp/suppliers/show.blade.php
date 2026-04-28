<x-layouts.ampp :title="$supplier->company" :breadcrumbs="Breadcrumbs::render('showSupplier', $supplier)">
    <div class="container">
        <x-ui.page-title>{{ $supplier->company }}</x-ui.page-title>

        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        {{ $supplier->company }}
                    </div>

                    <div class="card-body">
                        <div class="data-row">
                            <span>{{ __('First name') }}</span>
                            {{ $supplier->first_name }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Last name') }}</span>
                            {{ $supplier->last_name }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Company') }}</span>
                            {{ $supplier->company }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Vat') }}</span>
                            {{ $supplier->vat }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Iban') }}</span>
                            {{ $supplier->iban }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Email') }}</span>
                            <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                        </div>

                        <div class="data-row">
                            <span>{{ __('Phone') }}</span>
                            {{ $supplier->phone }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Address') }}</span>
                            <span class="text-md-end">{!! $supplier->address !!}</span>
                        </div>

                        <div class="data-row">
                            <span>{{ __('Default invoice category') }}</span>
                            {{ $supplier->invoiceCategory?->name ?? '/' }}
                        </div>

                        <div class="rounded-3 overflow-hidden">
                            <iframe
                                src="{{ $supplier->google_maps_url }}"
                                class="w-100 shadow-lg"
                                style="height: 300px"
                            ></iframe>
                        </div>

                        @if($supplier->notes)
                            <hr>

                            <div class="mt-4">
                                <x-ui.quill-display :content="$supplier->notes" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        {{ __('Actions') }}
                    </div>

                    <div class="card-body d-grid gap-4">
                        <a href="{{ action(\App\Http\Controllers\Ampp\Suppliers\EditSupplierController::class, $supplier) }}" class="btn btn-primary">
                            {{ __('Edit supplier') }}
                        </a>

                        <a type="button" data-bs-toggle="modal" data-bs-target="#confirmToggleClearfactsModal" class="btn btn-outline-primary w-100 mb-4">
                            @if($supplier->is_disabled_for_clearfacts)
                                {{ __('Enable Clearfacts bulk for this supplier') }}
                            @else
                                {{ __('Disable Clearfacts bulk for this supplier') }}
                            @endif
                        </a>

                        <button type="button" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" class="btn btn-danger">
                            {{ __('Delete supplier') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                {{ __('Expenses') }}
            </div>

            <div class="card-body">
                @include('ampp.suppliers.partials.expenses')
            </div>
        </div>
    </div>

    <x-push name="modals">
        <x-ui.confirmation-modal id="confirmToggleClearfactsModal">
            <x-slot name="content">
                @if($supplier->is_disabled_for_clearfacts)
                    <p>
                        {{ __('Are you sure you want to enable Clearfacts bulk for this supplier?') }}
                    </p>
                @else
                    <p>
                        {{ __('Are you sure you want to disable Clearfacts bulk for this supplier?') }}
                    </p>

                    <p class="text-muted">
                        {{ __('All of the listed expenses below that aren\'t uploaded to Clearfacts yet will not be visible when bulk uploading to Clearfacts. You can still manually upload an expense to Clearfacts on the expense page itself.') }}
                    </p>
                @endif
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\Suppliers\ToggleClearfactsController::class, $supplier)" method="patch">
                    @if($supplier->is_disabled_for_clearfacts)
                        <button class="btn btn-primary">{{ __('Yes, enable Clearfacts bulk') }}</button>
                    @else
                        <button class="btn btn-danger">{{ __('Disable Clearfacts bulk') }}</button>
                    @endif
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>

        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this supplier? This action can not be reverted!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\Suppliers\DestroySupplierController::class, $supplier)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
