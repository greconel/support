<x-layouts.ampp :title="$client->full_name" :breadcrumbs="Breadcrumbs::render('showClient', $client)">
    <div class="container">
        <x-ui.page-title>{{ $client->full_name }} {{ $client->company ? '- '. $client->company : '' }}</x-ui.page-title>

        @if($client->deleted_at)
            <x-ui.alert class="alert-warning">
                {{ __('This is an archived client') }}
            </x-ui.alert>
        @endif

        <div class="row mb-4">
            <div class="col-lg-5">
                @include('ampp.clients.partials.details', ['client' => $client])
            </div>

            <div class="col-lg-7">
                @include('ampp.clients.partials.contacts', ['client' => $client])

                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        {{ __('Projects') }}
                    </div>
                    <div class="card-body">
                    <div class="overflow-auto" style="max-height: 500px">
                        @forelse($client->projects as $index => $project)
                            <p><a href="{{ action(\App\Http\Controllers\Ampp\Projects\ShowProjectOverviewController::class, $project) }}">{{ $project->name }}</a></p>
                        @empty
                            <p class="text-muted text-center">{{ __('No projects yet') }}</p>
                        @endforelse
                    </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        {{ __('To do\'s') }}

                        <a href="#createTodoModal" data-bs-toggle="modal">{{ __('Create new to do') }}</a>
                    </div>

                    <div class="card-body">
                        <livewire:ampp.todos.index :model="$client" />
                    </div>
                </div>

                <livewire:ampp.clients.files :client="$client" />
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                {{ __('Notes') }}

                <a href="#createNoteModal" data-bs-toggle="modal">{{ __('Create new note') }}</a>
            </div>

            <div class="card-body">
                <livewire:ampp.notes.index :model="$client" />
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#invoices" type="button">
                            {{ __('Invoices') }}
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#quotations" type="button">
                            {{ __('Quotations') }}
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="invoices">
                        @include('ampp.clients.partials.invoices')
                    </div>

                    <div class="tab-pane fade" id="quotations">
                        @include('ampp.clients.partials.quotations')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-push name="modals">
        <livewire:ampp.todos.create-modal :model="$client" />
        <livewire:ampp.todos.edit-modal />
        <livewire:ampp.media.preview-modal />
        <livewire:ampp.notes.create-modal :model="$client" />
        <livewire:ampp.notes.edit-modal />

        @if(! $client->deleted_at)
            <x-ui.confirmation-modal id="confirmArchiveModal">
                <x-slot name="content">
                    {{ __('Are you sure you want to archive this client? The user will no longer appear in client selection dropdowns.') }}
                </x-slot>

                <x-slot name="actions">
                    <x-forms.form :action="action(\App\Http\Controllers\Ampp\Clients\ArchiveClientController::class, $client)" method="patch">
                        <button class="btn btn-primary">{{ __('Archive') }}</button>
                    </x-forms.form>
                </x-slot>
            </x-ui.confirmation-modal>
        @else
            <x-ui.confirmation-modal id="confirmRestoreModal">
                <x-slot name="content">
                    {{ __('Are you sure you want to restore this client? The client will appear back in client selection dropdowns.') }}
                </x-slot>

                <x-slot name="actions">
                    <x-forms.form :action="action(\App\Http\Controllers\Ampp\Clients\RestoreClientController::class, $client)" method="patch">
                        <button class="btn btn-primary">{{ __('Restore') }}</button>
                    </x-forms.form>
                </x-slot>
            </x-ui.confirmation-modal>
        @endif

        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this client? This action can not be reverted!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\Clients\DestroyClientController::class, $client)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
