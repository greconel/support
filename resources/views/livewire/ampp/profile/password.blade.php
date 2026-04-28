<div>
    <x-ui.session-alert session="success" class="alert-success" />
    <x-ui.session-alert session="error" class="alert-danger" />

    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.label for="oldPassword">{{ __('Current password') }}</x-forms.label>
            <x-forms.input type="password" name="oldPassword" required wire:model.lazy="oldPassword" />
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.label for="password">{{ __('New password') }}</x-forms.label>
            <x-forms.input type="password" name="password" required wire:model.lazy="password" />
        </div>

        <div class="col-12 mb-3">
            <x-forms.label for="password_confirm">{{ __('Confirm new password') }}</x-forms.label>
            <x-forms.input type="password" name="password_confirm" required wire:model.lazy="password_confirmation" />
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-sm btn-primary" wire:click="edit">{{ __('Update password') }}</button>
    </div>
</div>
