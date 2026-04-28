<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bolder fs-4">{{ __('Details') }}</span>

        <div class="d-flex align-items-center">
            <a href="#quotationEditModal" class="me-3" data-bs-toggle="modal">{{ __('Edit') }}</a>

            <div class="dropdown">
                <a class="link-secondary ps-2" href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </a>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li>
                        <a href="#confirmDeleteModal" data-bs-toggle="modal" class="dropdown-item link-danger">
                            <i class="fas fa-trash-alt me-2"></i> {{ __('Delete') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="data-row">
            <span>{{ __('Amount excl. VAT') }}</span>
            {{ $quotation->amount_formatted ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Amount incl. VAT') }}</span>
            {{ $quotation->amount_with_vat_formatted ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Created at') }}</span>
            {{ $quotation->custom_created_at->format('d/m/Y') }}
        </div>

        <div class="data-row">
            <span>{{ __('Expiration date') }}</span>
            {{ $quotation->expiration_date->format('d/m/Y') }}
        </div>

        <hr>

        <h4 class="data-subtitle">{{ __('Client') }}</h4>

        <div class="data-row">
            <span>{{ __('Name') }}</span>
            <a href="{{ action(\App\Http\Controllers\Ampp\Clients\ShowClientController::class, $quotation->client) }}" target="_blank">
                {{ $quotation->client->full_name }}
            </a>
        </div>

        <div class="data-row">
            <span>{{ __('Type') }}</span>
            <span class="text-{{ $quotation->client->type->color() }}">
                {{ $quotation->client->type->label() }}
            </span>
        </div>

        <div class="data-row">
            <span>{{ __('Email') }}</span>
            <a href="mailto:{{ $quotation->client->email }}">
                {{ $quotation->client->email }}
            </a>
        </div>

        <div class="data-row">
            <span>{{ __('Company') }}</span>
            {{ $quotation->client->company }}
        </div>

        <hr>

        <div
            @class([
                'row justify-content-between align-items-start',
                'mb-3' => $quotation->status == \App\Enums\QuotationStatus::Accepted,
                'mb-5' => $quotation->status != \App\Enums\QuotationStatus::Accepted
            ])
        >
            <div class="col">
                <span class="data-subtitle">{{ __('Status') }}</span>
            </div>

            <div class="col-md-5">
                <select name="status" class="form-select form-select-sm" x-data @change="$wire.call('updateStatus')" wire:model="status">
                    <option value="" disabled selected hidden>{{ __('Change status') }}</option>

                    @foreach(\App\Enums\QuotationStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ __($status->label()) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($quotation->status == \App\Enums\QuotationStatus::Accepted)
            <div class="row justify-content-between align-items-center mb-5">
                <div class="col">
                    <span class="data-subtitle">{{ __('Accepted at') }}</span>
                </div>

                <div class="col-md-5">
                    <x-forms.input type="date" name="accepted_at" wire:model="acceptedAt" class="form-control-sm" />
                </div>
            </div>
        @endif

        <div class="text-center fs-4 text-secondary text-uppercase mb-3">{{ $quotation->status->label() }}</div>

        <div class="progress">
            <div
                class="progress-bar bg-{{ $quotation->status->color() }}"
                style="width: {{ $quotation->status->progress() }}%"
                aria-valuenow="{{ $quotation->status->progress() }}"
                role="progressbar" aria-valuemin="0" aria-valuemax="100"
            ></div>
        </div>
    </div>
</div>

