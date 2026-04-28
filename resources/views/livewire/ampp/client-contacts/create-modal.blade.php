<div
    class="modal fade"
    id="createModal"
    aria-labelledby="createModalLabel"
    aria-hidden="true"
    wire:ignore.self
    x-data="{ modal: new bootstrap.Modal($refs.modal) }"
    x-init="
        $wire.on('show', () => modal.show());
    "
    x-ref="modal"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Choose a client where you want to attach the new contact to') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form wire:submit.prevent="submit">
                    <div
                        x-data
                        x-init="
                            new TomSelect($refs.clients, { allowEmptyOption: true, hidePlaceholder: true });
                        "
                        wire:ignore
                    >
                        <x-forms.label for="client_id">{{ __('Client') }}</x-forms.label>
                        <x-forms.select
                            name="client_id"
                            :options="$clients"
                            x-ref="clients"
                            required
                            wire:model="selectedClient"
                        />
                    </div>

                    <x-forms.error-message for="selectedClient" />
                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

                <button type="button" class="btn btn-primary" wire:click="submit" wire:target="submit" wire:loading.attr="disabled">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:target="submit" wire:loading></span>
                    {{ __('Continue') }}
                </button>
            </div>
        </div>
    </div>
</div>
