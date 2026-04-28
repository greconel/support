<x-layouts.auth>
    <h1 class="fw-bolder mb-4">{{ __('Reset password') }}</h1>

    <x-forms.form action="/reset-password" method="post">
        <x-forms.input type="hidden" name="token" :value="request()->route('token')" required />

        <div class="mb-3">
            <x-forms.label for="email" class="fw-bolder">{{ __('Email') }}</x-forms.label>
            <x-forms.input type="email" name="email" class="form-control-lg" required />
        </div>

        <div class="mb-3">
            <x-forms.label for="password" class="fw-bolder">{{ __('Password') }}</x-forms.label>
            <x-forms.input type="password" name="password" class="form-control-lg" required />
        </div>

        <div class="mb-3">
            <x-forms.label for="password_confirmation" class="fw-bolder">{{ __('Confirm password') }}</x-forms.label>
            <x-forms.input type="password" name="password_confirmation" class="form-control-lg" required />
        </div>

        <x-forms.submit class="btn-lg w-100">{{ __('Reset password') }}</x-forms.submit>
    </x-forms.form>
</x-layouts.auth>
