<x-layouts.ampp :title="__('Create connection')">
    <div class="container">
        <x-ui.page-title>{{ __('Create connection') }}</x-ui.page-title>

        <div class="card card-body">

            <x-forms.form
                action="{{ action(\App\Http\Controllers\Admin\Connections\StoreConnectionController::class) }}"
                method="post" :has-files="true">

                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                @endif

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                        <x-forms.input name="name" required/>
                    </div>
                    <div class="col-lg-6">
                        <x-forms.label for="short_description">{{ __('Short description') }}</x-forms.label>
                        <x-forms.input name="short_description"/>
                    </div>
                </div>
                <div class="mb-3" wire:key="description">
                    <x-forms.label for="description" class="fw-bolder">{{ __('Description') }}</x-forms.label>
                    <x-forms.quill name="description"/>
                    <x-forms.error-message for="description"/>
                </div>

                <div class="mb-3">
                    <livewire:admin.connections.create-connection/>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create connection') }}</x-forms.submit>
                </div>

            </x-forms.form>
        </div>

    </div>
</x-layouts.ampp>