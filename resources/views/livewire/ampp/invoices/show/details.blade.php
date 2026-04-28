<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bolder fs-4">{{ __('Details') }}</span>

        <div class="d-flex align-items-center">
            <a href="#invoiceEditModal" class="me-3" data-bs-toggle="modal">{{ __('Edit') }}</a>

            <div class="dropdown">
                <a class="link-secondary ps-2" href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </a>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li>
                        <a href="#confirmToggleClearfactsModal" data-bs-toggle="modal" class="dropdown-item">
                            @if($invoice->is_disabled_for_clearfacts)
                                <i class="fas fa-thumbs-up me-2"></i> {{ __('Enable this invoice for Clearfacts bulk') }}
                            @else
                                <i class="fas fa-hand-paper me-2"></i> {{ __('Disable this invoice for Clearfacts bulk') }}
                            @endif
                        </a>
                    </li>

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
            {{ $invoice->amount_formatted ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Amount incl. VAT') }}</span>
            {{ $invoice->amount_with_vat_formatted ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Type of invoice') }}</span>
            {{ $invoice->type->label() }}
        </div>

        <div class="data-row">
            <span>{{ __('Created at') }}</span>
            {{ $invoice->custom_created_at->format('d/m/Y') }}
        </div>

        <div class="data-row">
            <span>{{ __('Expiration date') }}</span>
            {{ $invoice->expiration_date->format('d/m/Y') }}
        </div>

        @if($invoice->type == \App\Enums\InvoiceType::Debit)
            <div class="data-row">
                <span>{{ __('Structured message (OGM)') }}</span>
                {{ $invoice->ogm }}
            </div>
        @endif

        @if($invoice->po_number)
            <div class="data-row">
                <span>{{ __('PO Number') }}</span>
                {{ $invoice->po_number }}
            </div>
        @endif

        <div class="data-row">
            <span>{{ __('Invoice category') }}</span>
            {{ $invoice->invoiceCategory?->name ?? '/' }}
        </div>

        <hr>

        <p class="text-uppercase lead mb-3">{{ __('Client') }}</p>

        <div class="data-row">
            <span>{{ __('Name') }}</span>
            <a href="{{ action(\App\Http\Controllers\Ampp\Clients\ShowClientController::class, $invoice->client) }}" target="_blank">
                {{ $invoice->client->full_name }}
            </a>
        </div>

        <div class="data-row">
            <span>{{ __('Email') }}</span>
            <a href="mailto:{{ $invoice->client->email }}">
                {{ $invoice->client->email }}
            </a>
        </div>

        <div class="data-row">
            <span>{{ __('Company') }}</span>
            {{ $invoice->client->company }}
        </div>

        <hr>

        <div
            @class([
                'row justify-content-between align-items-start',
                'mb-3' => $invoice->status == \App\Enums\InvoiceStatus::Paid,
                'mb-5' => $invoice->status != \App\Enums\InvoiceStatus::Paid
            ])
        >
            <div class="col">
                <span class="text-uppercase lead">{{ __('Status') }}</span>
            </div>

            <div class="col-md-5">
                <select name="status" class="form-select form-select-sm" x-data @change="$wire.call('updateStatus')" wire:model="status">
                    <option value="" disabled selected hidden>{{ __('Change status') }}</option>

                    @foreach(\App\Enums\InvoiceStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ __($status->label()) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($invoice->status == \App\Enums\InvoiceStatus::Paid)
            <div class="row justify-content-between align-items-start mb-5">
                <div class="col">
                    <span class="text-uppercase lead">{{ __('Paid at') }}</span>
                </div>

                <div class="col-md-5">
                    <x-forms.input type="date" name="paid_at" wire:model="paidAt" class="form-control-sm" />
                </div>
            </div>
        @endif

        <div class="text-center fs-4 text-secondary text-uppercase mb-3">{{ $invoice->status->label() }}</div>

        <div class="progress">
            <div
                class="progress-bar bg-{{ $invoice->status->color() }}"
                style="width: {{ $invoice->status->progress() }}%"
                aria-valuenow="{{ $invoice->status->progress() }}"
                role="progressbar" aria-valuemin="0" aria-valuemax="100"
            ></div>
        </div>

        <hr>

        <div class="d-flex flex-column align-items-center">
                @if($invoice->sent_to_clearfacts_at)
                    <span class="text-success mb-2">
                        {{ __('Uploaded to clearfacts at :date', ['date' => $invoice->sent_to_clearfacts_at?->format('d M Y H:i')]) }}
                    </span>
                @endif

                @if($invoice->is_disabled_for_clearfacts)
                    <span class="text-muted">
                        {{ __('This invoice is disabled for Clearfacts bulk') }}
                    </span>
                @endif

                <a
                    href="#confirmUploadClearfactsModal"
                    data-bs-toggle="modal"
                    class="btn btn-link link-primary"
                >
                    {{ __('Upload to Clearfacts') }}
                </a>

                <!-- Button to confirm that the invoice already exists in Clearfacts -->
                <!--<a
                    href="{{ action(\App\Http\Controllers\Ampp\Invoices\ConfirmInvoiceExistsInClearfactsController::class, $invoice) }}"
                    class="btn btn-link link-secondary mt-2"
                >
                    {{ __('This invoice already exists in Clearfacts') }}
                </a>-->
        </div>
    </div>
</div>

<x-push name="modals">
    <x-ui.confirmation-modal id="confirmDeleteModal">
        <x-slot name="content">
            {{ __('Are you sure you want to delete this invoice? This action can not be reverted!') }}
        </x-slot>

        <x-slot name="actions">
            <x-forms.form :action="action(\App\Http\Controllers\Ampp\Invoices\DestroyInvoiceController::class, $invoice)" method="delete">
                <button class="btn btn-danger">{{ __('Delete') }}</button>
            </x-forms.form>
        </x-slot>
    </x-ui.confirmation-modal>

    <x-ui.confirmation-modal id="confirmUploadClearfactsModal">
        <x-slot name="content">
            {{ __('Are you sure you want to upload this invoice to Clearfacts? This action can not be reverted!') }}
        </x-slot>

        <x-slot name="actions">
            <a
                href="{{ action(\App\Http\Controllers\Ampp\Invoices\UploadInvoiceToClearfactsController::class, $invoice) }}"
                class="btn btn-primary"
            >
                {{ __('Yes, upload to Clearfacts') }}
            </a>
        </x-slot>
    </x-ui.confirmation-modal>

    <x-ui.confirmation-modal id="confirmToggleClearfactsModal">
        <x-slot name="content">
            @if($invoice->is_disabled_for_clearfacts)
                <p>
                    {{ __('Are you sure you want to enable Clearfacts bulk for this invoice?') }}
                </p>
            @else
                <p>
                    {{ __('Are you sure you want to disable Clearfacts bulk for this invoice?') }}
                </p>

                <p class="text-muted">
                    {{ __('This invoice will not be visible when bulk uploading to Clearfacts. You can still manually upload this invoice to Clearfacts on this page.') }}
                </p>
            @endif
        </x-slot>

        <x-slot name="actions">
            <x-forms.form :action="action(\App\Http\Controllers\Ampp\Invoices\ToggleClearfactsController::class, $invoice)" method="patch">
                @if($invoice->is_disabled_for_clearfacts)
                    <button class="btn btn-primary">{{ __('Yes, enable Clearfacts bulk') }}</button>
                @else
                    <button class="btn btn-danger">{{ __('Disable Clearfacts bulk') }}</button>
                @endif
            </x-forms.form>
        </x-slot>
    </x-ui.confirmation-modal>
</x-push>
