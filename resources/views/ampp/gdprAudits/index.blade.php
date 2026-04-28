<x-layouts.ampp :title="__('GDPR audits')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All GDPR audits') }}</x-ui.page-title>

        <a href="{{ action(\App\Http\Controllers\Ampp\GdprAudits\CreateGdprAuditController::class) }}" class="btn btn-primary">
            {{ __('Create new GDPR audit') }}
        </a>
    </div>

    <div class="mb-4">
        {{ __('All measures and processes regarding the protection of personal data can be documented here. This audit is ideally suited to also keep track of and evaluate positive progress. Also, should there be a breach, this log is highly appreciated by the Privacy Commission. This is not only about the tough measures, but also about raising awareness and training staff.') }}
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="gdprAudit-table" :placeholder="__('Search GDPR audits...')" />
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="gdprAudit-table" :redirect="action(\App\Http\Controllers\Ampp\GdprAudits\EditGdprAuditController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
