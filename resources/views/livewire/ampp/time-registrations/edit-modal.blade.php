<div>
    <div
        class="modal fade"
        x-data="{ modal: new bootstrap.Modal($refs.modal) }"
        x-init="
            $wire.on('show', () => modal.show());
            $wire.on('hide', () => modal.hide());
        "
        x-ref="modal"
        wire:ignore.self
    >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit time registration') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form
                        wire:submit.prevent="update"
                        id="timeRegistrationEditForm"
                        x-data
                        @keydown.cmd.enter.prevent="$wire.call('update')"
                        @keydown.ctrl.enter.prevent="$wire.call('update')"
                    >
                        <div class="mb-3">
                            <x-forms.label for="project_id" class="fw-bolder">{{ __('Project') }}</x-forms.label>

                            <div
                                x-data="{ projectId: $wire.entangle('projectId').live, select: null }"
                                x-init="
                                    select = new TomSelect($refs.projects, { allowEmptyOption: true, hidePlaceholder: true });

                                    $watch('projectId', function() {
                                        select.addItem(projectId);
                                        select.refreshItems();
                                    });

                                    $wire.on('refreshProjects', () => select.addItem(''));
                                "
                                wire:ignore
                            >
                                <x-forms.select name="project_id" :options="$projects->sort()->toArray()" x-model="projectId" x-ref="projects" />
                            </div>

                            <x-forms.error-message for="timeRegistration.project_id" />
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label class="fw-bolder">{{ __('From') }}</label>

                                <div
                                    x-data="{ start: $wire.entangle('start') }"
                                    x-init="
                                        Inputmask('datetime', {
                                            inputFormat: 'HH:MM',
                                            placeholder: '_',
                                            onincomplete: (mask) => start = mask.target.value.replaceAll('_', 0)
                                        }).mask($refs.input);
                                    "
                                >
                                    <x-forms.input name="start" placeholder="{{ __('Start time') }}" required x-model="start" x-ref="input" />
                                </div>

                                <x-forms.error-message for="start" />
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label class="fw-bolder">{{ __('To') }}</label>

                                <div
                                    x-data="{ end: $wire.entangle('end') }"
                                    x-init="
                                        Inputmask('datetime', {
                                            inputFormat: 'HH:MM',
                                            placeholder: '_',
                                            onincomplete: (mask) => end = mask.target.value.replaceAll('_', 0)
                                        }).mask($refs.input);
                                    "
                                    class="flex-grow-1"
                                >
                                    <x-forms.input name="end" placeholder="{{ __('End time') }}" x-model="end" x-ref="input" />
                                </div>

                                <x-forms.error-message for="end" />
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label class="fw-bolder">{{ __('At') }}</label>

                                <div
                                    x-data="{ date: $wire.entangle('date').live, datepicker: null }"
                                    x-init="
                                        datepicker = flatpickr($refs.input, {
                                            altInput: true,
                                            altFormat: 'd/m/Y',
                                            dateFormat: 'Y-m-d',
                                            static: true
                                        });

                                        $watch('date', () => datepicker.setDate(date));
                                    "
                                    wire:ignore
                                    class="flex-grow-1"
                                >
                                    <x-forms.input name="date" required x-ref="input" x-model="date" />
                                </div>

                                <x-forms.error-message for="date" />
                            </div>
                        </div>

                        @if(count($this->projectActivities) > 0)
                            <div class="mb-3" wire:key="activities">
                                <label class="fw-bolder mb-1">{{ __('Activity') }}</label>

                                <div class="d-flex flex-wrap gap-2">
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        name="activity_id"
                                        value=""
                                        id="radio-no-activity"
                                        autocomplete="off"
                                        wire:model="projectActivityId"
                                    >

                                    <label
                                        for="radio-no-activity"
                                        class="btn btn-lg btn-outline-primary"
                                    >
                                        {{ __('-- No activity --') }}
                                    </label>

                                    @foreach($this->projectActivities as $activity)
                                        <div wire:key="activity-{{ $activity->id }}">
                                            <input
                                                type="radio"
                                                class="btn-check"
                                                name="activity_id"
                                                value="{{ $activity->id }}"
                                                id="radio-{{ $activity->id }}"
                                                autocomplete="off"
                                                wire:model="projectActivityId"
                                            >
                                            @if($activity->budget_in_hours != 0)
                                                <label
                                                    @class([
                                                        'btn',
                                                        'btn-lg',
                                                        'btn-outline-success' => ($activity->actual_hours / $activity->budget_in_hours) < 0.80,
                                                        'btn-outline-warning' => ($activity->actual_hours / $activity->budget_in_hours) >= 0.80 && (($activity->budget_in_hours - $activity->actual_hours) / $activity->budget_in_hours) < 0.90,
                                                        'btn-outline-danger' => ($activity->actual_hours / $activity->budget_in_hours) >= 0.90,
                                                    ])
                                                    for="radio-{{ $activity->id }}"
                                                    class="btn btn-lg btn-outline-primary"
                                                >
                                                    {{ $activity->name }}
                                                    ({{$activity->budget_in_hours - $activity->actual_hours}}h)

                                                    <div style="border-style: solid; border-width: 1px; border-color: #a6a6a6; background-color: lightgrey; height: 5px; border-radius: 12px">
                                                        <div
                                                            @class([
                                                                'bg-success' => ($activity->actual_hours / $activity->budget_in_hours) < 0.80,
                                                                'bg-warning' => ($activity->actual_hours / $activity->budget_in_hours) >= 0.80 && (($activity->budget_in_hours - $activity->actual_hours) / $activity->budget_in_hours) < 0.90,
                                                                'bg-danger' => ($activity->actual_hours / $activity->budget_in_hours) >= 0.90,
                                                            ])
                                                            style="height: 3px; border-radius: 12px; width: {{min(($activity->actual_hours / $activity->budget_in_hours) * 100, 100)}}%;">
                                                        </div>
                                                    </div>
                                                </label>
                                            @else
                                                <label for="radio-{{ $activity->id }}" class="btn btn-lg btn-outline-primary">
                                                    {{ $activity->name }}
                                                </label>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-3" wire:key="description">
                            <x-forms.label for="description" class="fw-bolder">{{ __('Description') }}</x-forms.label>
                            <x-forms.quill-livewire wire:model="description" />
                            <x-forms.error-message for="description" />
                        </div>
                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

                    <div>
                        <button type="button" class="btn btn-link link-secondary" data-bs-target="#confirmDeleteTimeRegistrationModal" data-bs-toggle="modal">
                            {{ __('Delete') }}
                        </button>

                        <button type="submit" form="timeRegistrationEditForm" class="btn btn-primary">
                            {{ __('Edit') }}
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <x-ui.confirmation-modal
        id="confirmDeleteTimeRegistrationModal"
        x-data="{ modal: new bootstrap.Modal($refs.modal) }"
        x-init="
            $wire.on('hide', () => modal.hide());
        "
        x-ref="modal"
        wire:ignore.self
    >
        <x-slot name="content">
            {{ __('Are you sure you want to delete this time registration? This action can not be reverted!') }}
        </x-slot>

        <x-slot name="actions">
            <button class="btn btn-danger" wire:click="delete">{{ __('Delete') }}</button>
        </x-slot>
    </x-ui.confirmation-modal>
</div>
