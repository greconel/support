<x-layouts.ampp :title="__('New ticket') . ' - Helpdesk'">
    @include('ampp.helpdesk.partials.styles')

    <div class="hd-scope">
        <div class="hd-page">

            <div class="hd-flex hd-items-center hd-gap-4 hd-mb-4">
                <a href="{{ route('tickets.index') }}" class="hd-text-link" style="color: var(--gray-600);">
                    <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="hd-h1" style="margin: 0;">{{ __('New ticket') }}</h1>
            </div>

            @if(session('error'))
                <div class="hd-alert hd-alert-error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('tickets.store') }}" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                @include('ampp.helpdesk.tickets.partials.form')

                <label class="hd-flex hd-items-center hd-gap-2 hd-mt-3" style="cursor: pointer;">
                    <input type="checkbox" name="send_confirmation" value="1" checked>
                    <span>Bevestigingsmail naar klant sturen</span>
                </label>

                <div class="hd-flex hd-gap-2 hd-mt-4">
                    <button type="submit" class="hd-btn hd-btn-primary" :disabled="submitting">
                        {{ __('Create ticket') }}
                    </button>
                    <a href="{{ route('tickets.index') }}" class="hd-btn hd-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-layouts.ampp>
