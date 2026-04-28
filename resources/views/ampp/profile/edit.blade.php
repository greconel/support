<x-layouts.ampp :title="__('My profile')">
    <div class="container">
        <x-ui.page-title>{{ __('My Profile') }}</x-ui.page-title>

        @include('ampp.profile.editPartials.profile', ['user' => $user])

        @include('ampp.profile.editPartials.password')

        @include('ampp.profile.editPartials.sessions')

        @include('ampp.profile.editPartials.tokens')
    </div>
</x-layouts.ampp>
