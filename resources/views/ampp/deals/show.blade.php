<x-layouts.ampp :title="$deal->name" :breadcrumbs="Breadcrumbs::render('showDeal', $deal)">
    <div class="container">
        <x-ui.page-title>{{ $deal->name }}</x-ui.page-title>

        <div class="row">
            <div class="col-lg-5">
                @include('ampp.deals.partials.details')
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        {{ __('To do\'s') }}

                        <a href="#createTodoModal" data-bs-toggle="modal">{{ __('Create new to do') }}</a>
                    </div>

                    <div class="card-body">
                        <livewire:ampp.todos.index :model="$deal" />
                    </div>
                </div>

                <livewire:ampp.deals.files :deal="$deal" />
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                {{ __('Notes') }}

                <a href="#createNoteModal" data-bs-toggle="modal">{{ __('Create new note') }}</a>
            </div>

            <div class="card-body">
                <livewire:ampp.notes.index :model="$deal" />
            </div>
        </div>
    </div>

    <x-push name="modals">
        <livewire:ampp.todos.create-modal :model="$deal" />
        <livewire:ampp.todos.edit-modal />
        <livewire:ampp.notes.create-modal :model="$deal" />
        <livewire:ampp.notes.edit-modal />
        <livewire:ampp.media.preview-modal />

        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this deal? This action can not be reverted!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\Deals\DestroyDealController::class, $deal)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
