<div
    class="modal fade"
    id="expenseClearfactsCommentModal"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    wire:ignore.self
    x-ref="modal"
    x-data="{ modal: new bootstrap.Modal($refs.modal) }"
    x-init="
        $refs.modal.addEventListener('show.bs.modal', () => $dispatch('refresh-quill'));
        $wire.on('close', () => modal.hide());
    "
>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit clearfacts comment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <x-forms.textarea name="clearfacts_comment" wire:model="expense.clearfacts_comment" />
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" wire:click="updateClearfactsComment">{{ __('Edit') }}</button>
            </div>
        </div>
    </div>
</div>

