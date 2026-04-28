<x-layouts.ampp :title="__('Project reports')">
    <div class="container-lg">
        <div class="page-header">
            <x-ui.page-title>{{ __('Project reports') }}</x-ui.page-title>
        </div>

        @if(session('error_details'))
            <div class="alert alert-danger" role="alert">
                {{ session('error_details') }}
            </div>
        @endif

        <livewire:ampp.date-range-filter />

        <livewire:ampp.project-reports.overview />
    </div>
</x-layouts.ampp>
