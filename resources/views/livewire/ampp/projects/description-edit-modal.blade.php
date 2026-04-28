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
                <form wire:submit.prevent="update" id="projectDescriptionEditForm" x-data>
                    <div class="mb-3" wire:key="description">
                        <x-forms.label for="description" class="fw-bolder">{{ __('Description') }}</x-forms.label>
                        <x-forms.quill-livewire wire:model="project.description" />
                        <x-forms.error-message for="project.description" />
                    </div>
                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

                <button type="submit" form="projectDescriptionEditForm" class="btn btn-primary">
                    {{ __('Edit') }}
                </button>
            </div>
        </div>

    </div>
</div>
