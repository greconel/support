<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        {{ $client->full_name }}

        <div class="d-flex align-items-center">
            <a href="{{ action(\App\Http\Controllers\Ampp\Clients\EditClientController::class, $client) }}" class="me-3">{{ __('Edit') }}</a>

            <div class="dropdown">
                <a class="link-secondary ps-2" href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </a>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    @if(! $client->deleted_at)
                        <li>
                            <a href="#confirmArchiveModal" data-bs-toggle="modal" class="dropdown-item link-secondary">
                                <i class="fas fa-archive me-2"></i> {{ __('Archive') }}
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="#confirmRestoreModal" data-bs-toggle="modal" class="dropdown-item link-secondary">
                                <i class="fas fa-trash-restore me-2"></i> {{ __('Restore') }}
                            </a>
                        </li>
                    @endif

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
            <span>{{ __('Type') }}</span>
            <span class="text-{{ $client->type->color() }}">
                {{ $client->type->label() }}
            </span>
        </div>

        <div class="data-row">
            <span>{{ __('Company name') }}</span>
            {{ $client->company }}
        </div>

        <div class="data-row">
            <span>{{ __('Vat') }}</span>
            {{ $client->vat }}
        </div>

        <div class="data-row">
            <span>{{ __('First name') }}</span>
            {{ $client->first_name }}
        </div>

        <div class="data-row">
            <span>{{ __('Last name') }}</span>
            {{ $client->last_name }}
        </div>

        <div class="data-row">
            <span>{{ __('Email') }}</span>
            <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
        </div>

        <div class="data-row">
            <span>{{ __('Phone') }}</span>
            <a href="tel:{{ $client->phone }}">{{ $client->phone }}</a>
        </div>

        <div class="data-row">
            <span>{{ __('Address') }}</span>
            <span class="text-md-end">{!! $client->address !!}</span>
        </div>

        <div class="data-row">
            <span>{{ __('Motion project ID') }}</span>
            {{ $client->motion_project_id }}
        </div>

        <div class="data-row">
            <span>{{ __('Invoice note') }}</span>
            <span class="text-md-end">{!! $client->invoice_note !!}</span>
        </div>

        <div class="data-row">
            <span>{{ __('Default invoice category') }}</span>
            {{ $client->invoiceCategory?->name ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Lat') }}</span>
            {{ $client->lat }}
        </div>

        <div class="data-row">
            <span>{{ __('Lng') }}</span>
            {{ $client->lng }}
        </div>

        <div class="rounded-3 overflow-hidden">
            <iframe
                src="{{ $client->google_maps_url }}"
                class="w-100"
                style="height: 300px"
            ></iframe>
        </div>

        @if($client->description)
            <hr>

            <div class="mt-4">
                <x-ui.quill-display :content="$client->description" />
            </div>
        @endif
    </div>
</div>
