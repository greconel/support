<div
    class="modal fade"
    id="createNoteModal"
    x-data="{ modal: new bootstrap.Modal($refs.modal) }"
    x-init="$wire.on('close', () => modal.hide())"
    x-ref="modal"
    wire:ignore.self
>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noteCreateModalLabel">{{ __('Create new note') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <x-forms.label for="note.title">{{ __('Title') }}</x-forms.label>
                    <x-forms.input name="note.title" wire:model="note.title" />
                </div>

                <div class="mb-3">
                    <x-forms.label for="note.description">{{ __('Description') }}</x-forms.label>
                    <x-forms.quill-livewire wire:model.lazy="note.description" />
                    <x-forms.error-message for="note.description" />
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" wire:click="create">{{ __('Create new note') }}</button>
            </div>
        </div>
    </div>
</div>
