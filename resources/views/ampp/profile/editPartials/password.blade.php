<div class="card card-body">
    <h4 class="mb-4">{{ __('Change password') }}</h4>

    <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Profile\UpdatePasswordController::class) }}" method="patch">
        <div class="col-lg-6 mb-3">
            <x-forms.label for="current_password">{{ __('Current password') }}</x-forms.label>
            <x-forms.input name="current_password" type="password" required />
        </div>

        <div class="row">
            <div class="col-lg-6 mb-3">
                <x-forms.label for="password">{{ __('New password') }}</x-forms.label>
                <x-forms.input name="password" type="password" required />
            </div>

            <div class="col-lg-6 mb-3">
                <x-forms.label for="password_confirmation">{{ __('Confirm new password') }}</x-forms.label>
                <x-forms.input name="password_confirmation" type="password" required />
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <x-forms.submit>{{ __('Update password') }}</x-forms.submit>
        </div>
    </x-forms.form>
</div>
