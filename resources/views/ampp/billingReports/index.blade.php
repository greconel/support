<x-layouts.ampp :title="__('Billing reports')">
    <div class="container">
        <div class="page-header">
            <x-ui.page-title>{{ __('Billing reports') }}</x-ui.page-title>
        </div>

        @if(session('error_details'))
            <div class="alert alert-danger" role="alert">
                {{ session('error_details') }}
            </div>
        @endif

        <livewire:ampp.date-range-filter :default-all-time="true" />

        <livewire:ampp.billing-reports.overview />
    </div>
</x-layouts.ampp>
