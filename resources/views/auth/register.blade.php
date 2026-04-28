<x-layouts.auth>
    <h1 class="fw-bolder mb-4">{{ __('Register') }}</h1>

    <x-forms.form action="{{ route('register') }}" method="post">
        <div class="mb-3">
            <x-forms.label for="name" class="fw-bolder">{{ __('Name') }}</x-forms.label>
            <x-forms.input name="name" class="form-control-lg" required />
        </div>

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

        <x-forms.submit class="btn-lg w-100">{{ __('Register') }}</x-forms.submit>
    </x-forms.form>
</x-layouts.auth>
