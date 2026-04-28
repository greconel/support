<div>
    <p class="mb-5">{{ __('If you encountered any problems or difficulties with AMPP, please do not hesitate to contact us by filling in the form below. We receive these messages immediately and will contact you A.S.A.P to help you or fix the issue.') }}</p>

    <x-ui.session-alert class="alert-success" session="send_success" />

    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.label for="title">{{ __('Title') }}</x-forms.label>
            <x-forms.input name="title" wire:model.lazy="title" />
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.label for="message">{{ __('Message') }}</x-forms.label>
            <x-forms.textarea name="message" wire:model.lazy="message" rows="5"></x-forms.textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.file name="images" accept="image/*" wire:model="images" multiple />
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button wire:loading class="btn btn-primary" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span class="visually-hidden">Loading...</span>
        </button>

        <x-forms.submit wire:click="send" wire:loading.remove>{{ __('Send') }}</x-forms.submit>
    </div>
</div>
