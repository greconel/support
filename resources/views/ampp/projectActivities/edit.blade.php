<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('editProject', $project)">
    <x-ampp.projects.layout :project="$project">
        <h2 class="mb-4">{{ __('Edit activity') }}</h2>

        <x-forms.form
            :action="action(\App\Http\Controllers\Ampp\ProjectActivities\UpdateProjectActivityController::class, [$project, $projectActivity])"
            method="patch"
            id="create-form"
        >
            <div class="row">
                <div class="mb-3 col-md-6">
                    <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                    <x-forms.input name="name" :value="$projectActivity->name" required />
                </div>

                <div class="mb-3 col-md-6">
                    <x-forms.label for="budget_in_hours">{{ __('Budget in hours') }}</x-forms.label>
                    <x-forms.input type="number" name="budget_in_hours" :value="$projectActivity->budget_in_hours" step="0.01" min="0" />
                </div>
            </div>

            <div class="mb-3">
                <x-forms.label for="description">{{ __('Description') }}</x-forms.label>
                <x-forms.textarea name="description">{{ $projectActivity->description }}</x-forms.textarea>
            </div>

            <div class="mb-3">
                <x-forms.checkbox name="is_active" value="1" :checked="$projectActivity->is_active">
                    {{ __('Users can register time on this activity') }}
                </x-forms.checkbox>
            </div>
        </x-forms.form>

        <div class="d-flex justify-content-between align-items-center">
            <a href="#confirmDeleteModal" data-bs-toggle="modal" class="link-danger">{{ __('Delete') }}</a>

            <button type="submit" form="create-form" class="btn btn-primary">{{ __('Save') }}</button>
        </div>
    </x-ampp.projects.layout>

    <x-push name="modals">
        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this activity? All time registrations containing this activity will not be deleted but will lose the relation to this activity!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\ProjectActivities\DeleteProjectActivityController::class, $projectActivity)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
