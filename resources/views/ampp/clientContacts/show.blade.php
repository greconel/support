<x-layouts.ampp :title="$contact->full_name" :breadcrumbs="Breadcrumbs::render('showClientContact', $contact)">
    <div class="container">
        <x-ui.page-title>{{ $contact->full_name }}</x-ui.page-title>

        @if($client->deleted_at)
            <x-ui.alert class="alert-warning">
                {{ __('Hey there! this contact is archived!') }}
            </x-ui.alert>
        @endif

        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        {{ $contact->full_name }}
                    </div>

                    <div class="card-body">
                        <div class="data-row">
                            <span>{{ __('First name') }}</span>
                            {{ $contact->first_name }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Last name') }}</span>
                            {{ $contact->last_name }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Email') }}</span>
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        </div>

                        <div class="data-row">
                            <span>{{ __('Phone') }}</span>
                            {{ $contact->phone }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Tags') }}</span>
                            {{ $contact->tags ? implode(', ', $contact->tags) : '' }}
                        </div>

                        @if($contact->description)
                            <hr>

                            <div class="mt-4">
                                <x-ui.quill-display :content="$contact->description" />
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
                        <a href="{{ action(\App\Http\Controllers\Ampp\ClientContacts\EditClientContactController::class, $contact) }}" class="btn btn-primary">
                            {{ __('Edit contact') }}
                        </a>

                        <button type="button" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" class="btn btn-danger">
                            {{ __('Delete contact') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-push name="modals">
        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this contact? This action can not be reverted!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\ClientContacts\DestroyClientContactController::class, $contact)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
