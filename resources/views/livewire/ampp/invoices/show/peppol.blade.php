<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bolder fs-4">{{ __('Peppol') }}</span>

        @if($invoice->client->validated_vat && $peppolStatus['canReceive'] && empty($peppolValidationErrors))
            @if(!$invoice->sent_to_recommand_at)
                <button type="button" class="btn btn-sm btn-primary" onclick="confirm('{{ __('Are you sure you want to send this invoice via Peppol?') }}') || event.stopImmediatePropagation()" wire:click="sendPeppol" wire:loading.attr="disabled" wire:target="sendPeppol">
                    <i class="fas fa-paper-plane me-1"></i>
                    {{ __('Send via Peppol') }}
                    <span wire:loading wire:target="sendPeppol" class="spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>
                </button>
            @else
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="confirm('{{ __('Are you sure you want to resend this invoice via Peppol?') }}') || event.stopImmediatePropagation()" wire:click="sendPeppol" wire:loading.attr="disabled" wire:target="sendPeppol">
                    <i class="fas fa-redo me-1"></i>
                    {{ __('Resend') }}
                    <span wire:loading wire:target="sendPeppol" class="spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>
                </button>
            @endif
        @endif
    </div>

    <div class="card-body">
        @if(session()->has('peppol_error'))
            <div class="alert alert-danger">{{ session('peppol_error') }}</div>
        @endif

        @if($invoice->client?->peppol_only)
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-1"></i>
                {{ __('This client wants to receive invoices only via Peppol.') }}
            </div>
        @endif

        @if(! $invoice->sent_to_recommand_at)
            @if(!$invoice->client->validated_vat)
                <div class="text-center text-muted">{{ __('Client has no validated VAT number') }}</div>
            @elseif(!$peppolStatus['canReceive'])
                <div class="text-center text-muted">{{ $peppolStatus['reason'] ?? __('Recipient cannot receive via Peppol') }}</div>
            @elseif(!empty($peppolValidationErrors))
                <div class="alert alert-warning mb-0">
                    <strong>{{ __('Validation errors') }}:</strong>
                    <ul class="mb-0 mt-1" style="font-size: 0.9em;">
                        @foreach($peppolValidationErrors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center text-muted">{{ __('Ready to send via Peppol') }}</div>
            @endif
        @else
            <p class="mb-1"><strong>{{ __('Sent at') }}:</strong> {{ $invoice->sent_to_recommand_at->format('d/m/Y H:i') }}</p>
            <p class="mb-0"><strong>{{ __('Document ID') }}:</strong> {{ $invoice->recommand_document_id }}</p>
        @endif
    </div>
</div>
