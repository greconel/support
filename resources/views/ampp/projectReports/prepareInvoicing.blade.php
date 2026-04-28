<x-layouts.ampp :title="__('Prepare invoicing')" :breadcrumbs="Breadcrumbs::render('prepareInvoicing')">
    @php
        function formatDuration($seconds) {
            $h = floor($seconds / 3600);
            $m = floor(($seconds % 3600) / 60);
            if ($h > 0 && $m > 0) return "{$h}h {$m}m";
            if ($h > 0) return "{$h}h";
            return "{$m}m";
        }
    @endphp
    <div class="page-header d-flex justify-content-between align-items-center">
        <x-ui.page-title>{{ __('Prepare invoicing') }} — {{ $client?->full_name_with_company }}</x-ui.page-title>
    </div>

    <form action="{{ action(\App\Http\Controllers\Ampp\ProjectReports\StoreInvoiceFromTimeReportController::class) }}" method="POST">
        @csrf
        <input type="hidden" name="client_id" value="{{ $client?->id }}">
        @foreach($ids as $id)
            <input type="hidden" name="ids[]" value="{{ $id }}">
        @endforeach

        {{-- Notes & PO --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Invoice details') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="fw-bolder mb-1">{{ __('PO Number') }}</label>
                        <input type="text" name="po_number" class="form-control" value="{{ old('po_number') }}" placeholder="{{ __('Purchase order reference...') }}">
                    </div>
                    <div class="col-md-8">
                        <label class="fw-bolder mb-1">{{ __('Notes') }}</label>
                        <textarea name="notes" class="form-control" rows="1" placeholder="{{ __('Optional notes for this invoice...') }}">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        @foreach($sections as $index => $section)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-layer-group me-2 text-primary"></i>
                        {{ $section['activity_name'] }}
                    </h5>
                    <div class="d-flex gap-3">
                        <span class="badge bg-primary fs-6">
                            {{ __('Total') }}: {{ formatDuration($section['total_seconds']) }}
                        </span>
                        <span class="badge bg-success fs-6">
                            {{ __('Billable') }}: {{ formatDuration($section['billable_seconds']) }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Start') }}</th>
                                <th>{{ __('End') }}</th>
                                <th class="text-end">{{ __('Duration') }}</th>
                                <th class="text-center">{{ __('Billable') }}</th>
                                <th>{{ __('Description') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($section['time_registrations'] as $tr)
                                <tr>
                                    <td>{{ $tr->user?->name }}</td>
                                    <td>{{ $tr->start?->format('d/m/Y') }}</td>
                                    <td>{{ $tr->start?->format('H:i') }}</td>
                                    <td>{{ $tr->end?->format('H:i') }}</td>
                                    <td class="text-end">{{ formatDuration($tr->total_time_in_seconds) }}</td>
                                    <td class="text-center">
                                        @if($tr->is_billable)
                                            <i class="fas fa-check text-success"></i>
                                        @else
                                            <i class="fas fa-times text-danger"></i>
                                        @endif
                                    </td>
                                    <td>{!! $tr->description !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-light">
                    <input type="hidden" name="sections[{{ $index }}][total_seconds]" value="{{ $section['total_seconds'] }}">
                    <input type="hidden" name="sections[{{ $index }}][billable_seconds]" value="{{ $section['billable_seconds'] }}">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="fw-bolder mb-1">{{ __('Invoice line title') }} <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="sections[{{ $index }}][title]"
                                class="form-control @error('sections.' . $index . '.title') is-invalid @enderror"
                                value="{{ old('sections.' . $index . '.title', $section['activity_name']) }}"
                                placeholder="{{ __('Title for invoice line...') }}"
                                required
                            >
                            @error('sections.' . $index . '.title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="fw-bolder mb-1">{{ __('Invoice line description') }}</label>
                            <textarea
                                name="sections[{{ $index }}][description]"
                                class="form-control"
                                rows="1"
                                placeholder="{{ __('Extra description for invoice line...') }}"
                            >{{ old('sections.' . $index . '.description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-file-invoice me-2"></i> {{ __('Maak factuur') }}
            </button>
        </div>
    </form>
</x-layouts.ampp>
