<div>
    @if($timeRegistration->project?->is_general || ! $timeRegistration->project_id)
        <div
            x-data="{ projectId: $wire.entangle('projectId') }"
            x-init="new TomSelect($refs.projects, {});"
            wire:ignore
            wire:key="{{ time() . 'select' }}"
        >
            <x-forms.select name="project_id" :options="$projects" x-ref="projects" x-model="projectId" placeholder="{{ __('Change project') }}" />
        </div>
    @else
        <div class="d-flex flex-column gap-1" wire:key="{{ time() . 'undo' }}">
            <p class="mb-0 pb-0 text-center">{{ $timeRegistration->project?->name }}</p>
            <p class="text-gray-500 mb-0 pb-0 text-center small">{{ __('Filter again for changes to take effect!') }}</p>
            <button class="btn btn-link link-danger" wire:click="undo">{{ __('Choose other project') }}</button>
        </div>
    @endif
</div>
