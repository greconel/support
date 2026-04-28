<div
    class="modal fade"
    x-data="{
        modal: new bootstrap.Modal($refs.modal),
        endDate: $wire.entangle('endDate')
    }"
    x-init="
        flatpickr($refs.endDate, {
            altInput: true,
            altFormat: 'd/m/Y H:i',
            dateFormat: 'Y-m-d H:i',
            enableTime: true,
            time_24hr: true,
            static: true,
            plugins: [new scrollPlugin()]
        });

        $wire.on('open', () => {
            modal.show();
            $refs.endDate._flatpickr.setDate(new Date(endDate));
        })

         $wire.on('close', () => {
            modal.hide();
        })
    "
    x-ref="modal"
    wire:ignore.self
>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="todoEditModalLabel">{{ __('Edit todo') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <x-forms.label for="todo.title">{{ __('Title') }}</x-forms.label>
                    <x-forms.input name="todo.title" wire:model="todo.title" />
                </div>

                <div class="mb-3">
                    <label for="endDate" class="form-label">{{ __('End date') }}</label>
                    <div wire:ignore>
                        <input type="text" name="todo.end_date" x-ref="endDate" class="form-control" x-model="endDate">
                    </div>

                    @error('todo.end_date')
                    <div class="small text-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <x-forms.label for="todo.description">{{ __('Description') }}</x-forms.label>
                    <x-forms.quill-livewire wire:model.lazy="todo.description" />
                    <x-forms.error-message for="todo.description" />
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

                <div>
                    <button type="button" class="btn btn-link link-secondary" wire:click.prevent="delete">{{ __('Delete') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="update">{{ __('Edit') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
