@php
    /** @var \App\Models\Deal $deal */
@endphp

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ $deal->name }}</span>

        <div class="d-flex align-items-center">
            <a href="{{ action(\App\Http\Controllers\Ampp\Deals\EditDealController::class, $deal) }}" class="me-3">{{ __('Edit') }}</a>

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
            <span>{{ __('Inside of column') }}</span>
            {{ $deal->dealColumn->name }}
        </div>

        <div class="data-row mb-0">
            <span>{{ __('Due date') }}</span>

            <div>
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\Deals\UpdateDealDueDateController::class, $deal)" method="patch" x-data x-ref="form">
                    <x-forms.input type="datetime-local" name="due_date" :value="$deal->due_date" @input.debounce.1000ms="$refs.form.submit()" />
                </x-forms.form>
            </div>
        </div>

        @if($deal->due_date && $deal->due_date->isFuture())
            <p class="small text-muted text-end mb-3">{{ __('Reminder set at :date', ['date' => $deal->due_date->subhour()->format('d/m/Y H:i')]) }}</p>
        @endif

        <div class="data-row">
            <span>{{ __('Chance of success') }}</span>
            {{ $deal->chance_of_success }} %
        </div>

        <div class="data-row">
            <span>{{ __('Expected revenue') }}</span>
            € {{ $deal->expected_revenue ? $deal->expected_revenue_formatted : '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Expected start date') }}</span>
            {{ $deal->expected_start_date?->format('d/m/Y') }}
        </div>

        @if($deal->client_id)
            <h4 class="data-subtitle">{{ __('Client (lead)') }}</h4>

            <div class="data-row">
                <span>{{ __('Name') }}</span>

                <a href="{{ action(\App\Http\Controllers\Ampp\Clients\ShowClientController::class, $deal->client) }}" target="_blank">
                    {{ $deal->client->full_name }} <i class="fas fa-external-link-alt small"></i>
                </a>
            </div>

            <div class="data-row">
                <span>{{ __('Company') }}</span>
                {{ $deal->client->company }}
            </div>

            <div class="data-row">
                <span>{{ __('VAT') }}</span>
                {{ $deal->client->vat }}
            </div>

            <div class="data-row">
                <span>{{ __('Email') }}</span>
                <a href="mailto:{{ $deal->client->email }}">{{ $deal->client->email }}</a>
            </div>

            <div class="data-row">
                <span>{{ __('Phone') }}</span>
                <a href="tel:{{ $deal->client->phone }}">{{ $deal->client->phone }}</a>
            </div>
        @endif

        @if($deal->quotation_id)
            <h4 class="data-subtitle">{{ __('Quotation') }}</h4>

            <div class="data-row">
                <span>{{ __('Number') }}</span>

                <a href="{{ action(\App\Http\Controllers\Ampp\Quotations\ShowQuotationController::class, $deal->quotation) }}" target="_blank">
                    {{ $deal->quotation->number }} <i class="fas fa-external-link-alt small"></i>
                </a>
            </div>

            <div class="data-row">
                <span>{{ __('Amount incl. VAT') }}</span>
                {{ $deal->quotation->amount_with_vat_formatted }}
            </div>

            <div class="data-row">
                <span>{{ __('Status') }}</span>
                <span class="text-{{ $deal->quotation->status->color() }}">
                    {{ $deal->quotation->status->label() }}
                </span>
            </div>
        @else
            <div class="text-center mt-4">
                <a
                    href="{{ action(\App\Http\Controllers\Ampp\Quotations\CreateQuotationController::class, ['deal' => $deal->id, 'client' => $deal->client_id]) }}"
                    class="btn btn-link"
                >
                    {{ __('Create quotation for this lead') }}
                </a>
            </div>
        @endif

        @if($deal->description)
            <hr>

            <div class="mt-4">
                <x-ui.quill-display :content="$deal->description" />
            </div>
        @endif
    </div>
</div>
