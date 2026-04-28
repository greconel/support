<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('showProjectTodos', $project)">
    <x-ampp.projects.layout :project="$project">
        <div class="d-flex justify-content-end mb-3">
            <a href="#createTodoModal" data-bs-toggle="modal">
                {{ __('Create new to do') }}
            </a>
        </div>

        <livewire:ampp.todos.index :model="$project" />
    </x-ampp.projects.layout>

    <x-push name="modals">
        <livewire:ampp.todos.create-modal :model="$project" />
        <livewire:ampp.todos.edit-modal />
    </x-push>
</x-layouts.ampp>
