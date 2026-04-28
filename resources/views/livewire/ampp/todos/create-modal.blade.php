<div
    id="createTodoModal"
    x-data="{
        modal: new bootstrap.Modal($refs.modal),
        endDate: $wire.entangle('endDate')
    }"
    x-init="
        $wire.on('toggle', () => {
            modal.toggle();
        })
    "
    x-ref="modal"
    wire:ignore.self
    class="modal fade"
>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="todoCreateModalLabel">{{ __('Create new to do') }}</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <x-forms.label for="title">{{ __('Title') }}</x-forms.label>
                    <x-forms.input name="title" wire:model="title" />
                </div>

                <div class="mb-3">
                    <label for="end_date" class="form-label">{{ __('End date') }}</label>

                    <div
                        x-init="
                            flatpickr($refs.endDate, {
                                altInput: true,
                                altFormat: 'd/m/Y H:i',
                                dateFormat: 'Y-m-d H:i',
                                enableTime: true,
                                time_24hr: true,
                                plugins: [new scrollPlugin()]
                            });
                        "
                        wire:ignore
                    >
                        <input type="text" name="end_date" class="form-control" x-ref="endDate" x-model="endDate">
                    </div>

                    <x-forms.error-message for="endDate" />
                </div>

                <div class="mb-3">
                    <x-forms.label for="description">{{ __('Description') }}</x-forms.label>
                    <x-forms.quill-livewire wire:model.lazy="description" />
                    <x-forms.error-message for="description" />
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" wire:click="store">{{ __('Create') }}</button>
            </div>
        </div>
    </div>
</div>
