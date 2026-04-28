<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('showProjectFiles', $project)">
    <x-ampp.projects.layout :project="$project">
        <livewire:ampp.projects.files :project="$project" />
    </x-ampp.projects.layout>

    <x-push name="modals">
        <livewire:ampp.media.preview-modal />
    </x-push>
</x-layouts.ampp>
