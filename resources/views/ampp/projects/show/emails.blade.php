<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('showProjectEmails', $project)">
    <x-ampp.projects.layout :project="$project">
        <livewire:ampp.emails.list-for-model :email-model="$project" create-modal="projectEmailModal" />
    </x-ampp.projects.layout>

    <x-push name="modals">
        <livewire:ampp.projects.email-modal :project="$project" />
        <livewire:ampp.emails.preview-modal :email-model="$project" />
    </x-push>
</x-layouts.ampp>
