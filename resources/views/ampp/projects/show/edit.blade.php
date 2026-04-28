<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('editProject', $project)">
    <x-ampp.projects.layout :project="$project">
        <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Projects\UpdateProjectController::class, $project) }}" method="patch">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <x-forms.label for="name">{{ __('Name') }}*</x-forms.label>
                    <x-forms.input name="name" :value="$project->name" required />
                </div>

                <div
                    class="col-md-6 mb-3"
                    x-data
                    x-init="new TomSelect($refs.clients, { allowEmptyOption: true });"
                >
                    <x-forms.label for="client_id">{{ __('Client') }}</x-forms.label>
                    <x-forms.select
                        name="client_id"
                        :options="$clients"
                        :values="$project->client_id"
                        x-ref="clients"
                    />
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-3">
                    <x-forms.label for="budget_money">{{ __('Budget in euro') }}</x-forms.label>

                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <x-forms.input type="number" name="budget_money" :value="$project->budget_money" step=".01" />
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <x-forms.label for="budget_hours">{{ __('Budget in hours') }}</x-forms.label>
                    <x-forms.input type="number" name="budget_hours" :value="$project->budget_hours" step="1" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-forms.label for="category">{{ __('Category') }}</x-forms.label>

                    <x-forms.select name="category" :options="\App\Enums\ProjectCategory::ForSelect()" :values="$project->category?->value" />
                </div>

                <div class="col-md-6 mb-3">
                    <x-forms.label for="color">{{ __('Color') }}</x-forms.label>
                    <div
                        x-data="{ color: {{ json_encode($project->color)}} }"
                        x-init="
                                picker = new Picker({
                                    parent: $refs.button,
                                    color,
                                    onDone: rawColor => color = rawColor.hex,
                                    popup: 'bottom',
                                });
                            "
                    >
                        <input type="hidden" name="color" x-model="color">
                        <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
                    </div>
                    <x-forms.error-message for="color" />
                </div>
            </div>

            <div class="mb-3">
                <x-forms.label for="users[]">{{ __('Users') }}</x-forms.label>
                <x-forms.user-select
                    name="users[]"
                    id="users"
                    :users="$users"
                    :values="$project->users->pluck('id')->toArray()"
                    multiple
                    required
                />
            </div>

            <div class="mb-4">
                <x-forms.label for="description">{{ __('Description') }}</x-forms.label>
                <x-forms.quill name="description" :value="$project->description" />
                <x-forms.error-message for="description" />
            </div>

            <div class="mb-3">
                <x-forms.checkbox name="is_general" value="1" :checked="$project->is_general">
                    {{ __('This is a general project (time registrations for this project will act as if they belong to no project)') }}
                </x-forms.checkbox>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="#confirmDeleteModal" data-bs-toggle="modal" class="link-secondary">
                        {{ __('Delete') }}
                    </a>
                </div>

                <div>
                    @if(! $project->deleted_at)
                        <button type="button" data-bs-target="#confirmArchiveModal" data-bs-toggle="modal" class="btn btn-outline-primary">
                            {{ __('Archive') }}
                        </button>
                    @else
                        <button type="button" data-bs-target="#confirmRestoreModal" data-bs-toggle="modal" class="btn btn-outline-primary">
                            {{ __('Restore') }}
                        </button>
                    @endif

                    <x-forms.submit>{{ __('Edit project') }}</x-forms.submit>
                </div>
            </div>
        </x-forms.form>
    </x-ampp.projects.layout>

    <x-push name="modals">
        @if(! $project->deleted_at)
            <x-ui.confirmation-modal id="confirmArchiveModal">
                <x-slot name="content">
                    {{ __('Are you sure you want to archive this project? The user will no longer appear in client selection dropdowns.') }}
                </x-slot>

                <x-slot name="actions">
                    <x-forms.form :action="action(\App\Http\Controllers\Ampp\Projects\ArchiveProjectController::class, $project)" method="patch">
                        <button class="btn btn-primary">{{ __('Archive') }}</button>
                    </x-forms.form>
                </x-slot>
            </x-ui.confirmation-modal>
        @else
            <x-ui.confirmation-modal id="confirmRestoreModal">
                <x-slot name="content">
                    {{ __('Are you sure you want to restore this project? The client will appear back in client selection dropdowns.') }}
                </x-slot>

                <x-slot name="actions">
                    <x-forms.form :action="action(\App\Http\Controllers\Ampp\Projects\RestoreProjectController::class, $project)" method="patch">
                        <button class="btn btn-primary">{{ __('Restore') }}</button>
                    </x-forms.form>
                </x-slot>
            </x-ui.confirmation-modal>
        @endif

        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this project? This action can not be reverted!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\Projects\DestroyProjectController::class, $project)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
