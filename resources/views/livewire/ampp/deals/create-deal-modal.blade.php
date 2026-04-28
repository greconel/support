<div
    class="modal"
    x-data="{ modal: null }"
    x-init="
        modal = new bootstrap.Modal($refs.modal);

        $wire.on('toggle', () => modal.toggle());
    "
    x-ref="modal"
    wire:ignore.self
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{ __('Create new lead') }}
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                    <x-forms.input name="name" wire:model="deal.name" />
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" wire:click="store">{{ __('Create lead') }}</button>
            </div>
        </div>
    </div>
</div>

