<x-layouts.auth>
    <h1 class="fw-bolder">{{ __('Forgot your password?') }}</h1>
    <p class="text-gray-600 mb-4">{{ __('auth.forgot_password_message') }}</p>

    @if(session('status'))
        <x-ui.alert class="alert-success">{{ session('status') }}</x-ui.alert>
    @endif

    <x-forms.form action="/forgot-password" method="post">
        <div class="mb-3">
            <x-forms.label for="email" class="fw-bolder">{{ __('Email') }}</x-forms.label>
            <x-forms.input type="email" name="email" class="form-control-lg" required />
        </div>

        <x-forms.submit class="btn-lg w-100 mb-2">{{ __('Email password reset link') }}</x-forms.submit>

        <a href="{{ route('login') }}">{{ __('Back to log in') }}</a>
    </x-forms.form>
</x-layouts.auth>
