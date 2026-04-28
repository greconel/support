<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('editProject', $project)">
    <x-ampp.projects.layout :project="$project">
        <h2 class="mb-4">{{ __('Create new activity') }}</h2>

        <x-forms.form :action="action(\App\Http\Controllers\Ampp\ProjectActivities\StoreProjectActivityController::class, $project)">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                    <x-forms.input name="name" required />
                </div>

                <div class="mb-3 col-md-6">
                    <x-forms.label for="budget_in_hours">{{ __('Budget in hours') }}</x-forms.label>
                    <x-forms.input type="number" name="budget_in_hours" step="0.01" min="0" />
                </div>
            </div>

            <div class="mb-3">
                <x-forms.label for="description">{{ __('Description') }}</x-forms.label>
                <x-forms.textarea name="description"></x-forms.textarea>
            </div>

            <div class="mb-3">
                <x-forms.checkbox name="is_active" value="1" checked>{{ __('Users can register time on this activity') }}</x-forms.checkbox>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </x-forms.form>
    </x-ampp.projects.layout>
</x-layouts.ampp>
