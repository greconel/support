<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('showProjectNotes', $project)">
    <x-ampp.projects.layout :project="$project">
        <div class="d-flex justify-content-end mb-3">
            <a href="#createNoteModal" data-bs-toggle="modal">
                {{ __('Create new note') }}
            </a>
        </div>

        <livewire:ampp.notes.index :model="$project" />
    </x-ampp.projects.layout>

    <x-push name="modals">
        <livewire:ampp.notes.create-modal :model="$project" />
        <livewire:ampp.notes.edit-modal />
    </x-push>
</x-layouts.ampp>
