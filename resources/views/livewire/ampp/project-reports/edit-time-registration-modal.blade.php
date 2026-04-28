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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit time registration') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form
                        wire:submit.prevent="update"
                        id="editTimeRegistrationReportForm"
                        x-data
                        @keydown.cmd.enter.prevent="$wire.call('update')"
                        @keydown.ctrl.enter.prevent="$wire.call('update')"
                    >
                        @if($projectName)
                            <div class="mb-3">
                                <label class="fw-bolder">{{ __('Project') }}</label>
                                <p class="mb-0">{{ $projectName }}</p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="fw-bolder">{{ __('Billable') }}</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model="isBillable" id="isBillableSwitch">
                                <label class="form-check-label" for="isBillableSwitch">
                                    {{ $isBillable ? __('Yes') : __('No') }}
                                </label>
                            </div>
                        </div>

                        @if(count($this->projectActivities) > 0)
                            <div class="mb-3" wire:key="activities-{{ $timeRegistrationId }}">
                                <label class="fw-bolder mb-1">{{ __('Activity') }}</label>

                                <div class="d-flex flex-wrap gap-2">
                                    <input
                                        type="radio"
                                        class="btn-check"
                                        name="report_activity_id"
                                        value=""
                                        id="report-radio-no-activity"
                                        autocomplete="off"
                                        wire:model="projectActivityId"
                                    >
                                    <label for="report-radio-no-activity" class="btn btn-outline-primary">
                                        {{ __('-- No activity --') }}
                                    </label>

                                    @foreach($this->projectActivities as $activity)
                                        <div wire:key="report-activity-{{ $activity->id }}">
                                            <input
                                                type="radio"
                                                class="btn-check"
                                                name="report_activity_id"
                                                value="{{ $activity->id }}"
                                                id="report-radio-{{ $activity->id }}"
                                                autocomplete="off"
                                                wire:model="projectActivityId"
                                            >
                                            <label for="report-radio-{{ $activity->id }}" class="btn btn-outline-primary">
                                                {{ $activity->name }}
                                            </label>
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
                    <button type="submit" form="editTimeRegistrationReportForm" class="btn btn-primary">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>