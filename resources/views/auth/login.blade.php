<x-layouts.auth>
    <h1 class="fw-bolder">{{ __('Welcome back') }}</h1>
    <p class="text-gray-600 mb-4">{{ __('Welcome back! Please enter your credentials') }}</p>

    @if(session('status'))
        <x-ui.alert class="alert-success">{{ session('status') }}</x-ui.alert>
    @endif

    <x-forms.form action="{{ route('login') }}">
        <div class="mb-3">
            <x-forms.label for="email" class="fw-bolder">{{ __('Email') }}</x-forms.label>
            <x-forms.input type="email" name="email" class="form-control-lg" required />
        </div>

        <div class="mb-3">
            <x-forms.label for="password" class="fw-bolder">{{ __('Password') }}</x-forms.label>
            <x-forms.input type="password" name="password" class="form-control-lg" required />
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-forms.checkbox name="remember" checked>{{ __('Remember me') }}</x-forms.checkbox>
            <a href="/forgot-password" class="small">{{ __('Forgot your password?') }}</a>
        </div>

        <x-forms.submit class="btn-lg w-100">{{ __('Sign in') }}</x-forms.submit>
    </x-forms.form>
</x-layouts.auth>
