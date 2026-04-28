<div class="card mb-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bolder fs-4">{{ __('Details') }}</span>

        <div class="d-flex align-items-center">
            <a href="{{ action(\App\Http\Controllers\Ampp\Expenses\EditExpenseController::class, $expense) }}" class="me-3">{{ __('Edit') }}</a>

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
            <span>{{ __('Name') }}</span>
            {{ $expense->name }}
        </div>

        <div class="data-row">
            <span>{{ __('Number') }}</span>
            {{ $expense->number }}
        </div>

        <div class="data-row">
            <span>{{ __('Various transaction category') }}</span>
            {{ $expense->various_transaction_category?->label() }}
        </div>

        <div class="data-row">
            <span>{{ __('Invoice category') }}</span>
            {{ $expense->invoiceCategory?->name ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Amount excl. VAT') }}</span>
            {{ $expense->amount_excluding_vat_formatted ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Amount incl. VAT') }}</span>
            {{ $expense->amount_including_vat_formatted ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Amount of VAT in euro') }}</span>
            {{ $expense->amount_vat_formatted ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Amount of VAT in percentage') }}</span>
            {{ $expense->amount_vat_percentage ?? '/' }} %
        </div>

        <div class="data-row">
            <span>{{ __('Invoice date') }}</span>
            {{ $expense->invoice_date->format('d/m/Y') }}
        </div>

        <div class="data-row">
            <span>{{ __('Invoice number') }}</span>
            {{ $expense->invoice_number ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Invoice structured message (OGM)') }}</span>
            {{ $expense->invoice_ogm ?? '/' }}
        </div>

        <hr>

        <p class="text-uppercase lead mb-3">{{ __('Supplier') }}</p>

        <div class="data-row">
            <span>{{ __('Company') }}</span>
            <a href="{{ action(\App\Http\Controllers\Ampp\Suppliers\ShowSupplierController::class, $expense->supplier) }}" target="_blank">
                {{ $expense->supplier->company }}
            </a>
        </div>

        <div class="data-row">
            <span>{{ __('Name') }}</span>
            {{ $expense->supplier->name ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('VAT') }}</span>
            {{ $expense->supplier->vat ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Iban') }}</span>
            {{ $expense->supplier->iban ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Email') }}</span>
            @if($expense->supplier->email)
                <a href="mailto:{{ $expense->supplier->email }}">
                    {{ $expense->supplier->email }}
                </a>
            @else
                /
            @endif
        </div>

        <hr>

        <div
            @class([
                'row justify-content-between align-items-start',
                'mb-3' => $expense->status == \App\Enums\ExpenseStatus::Paid,
                'mb-5' => $expense->status != \App\Enums\ExpenseStatus::Paid
            ])
        >
            <div class="col">
                <span class="text-uppercase lead">{{ __('Status') }}</span>
            </div>

            @can('changeStatus', $expense)
                <div class="col-md-5">
                    <select name="status" class="form-select form-select-sm" x-data @change="$wire.call('updateStatus')" wire:model="status">
                        <option value="" disabled selected hidden>{{ __('Change status') }}</option>

                        @foreach(\App\Enums\ExpenseStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ __($status->label()) }}</option>
                        @endforeach
                    </select>
                </div>
            @endcan
        </div>

        @if($expense->status == \App\Enums\ExpenseStatus::Paid)
            <div class="row justify-content-between align-items-start mb-5">
                <div class="col">
                    <span class="text-uppercase lead">{{ __('Paid at') }}</span>
                </div>

                <div class="col-md-5">
                    <x-forms.input type="date" name="paid_at" wire:model="paidAt" class="form-control-sm" />
                </div>
            </div>
        @endif

        <div class="text-center fs-4 text-secondary text-uppercase mb-3">{{ $expense->status->label() }}</div>

        <div class="progress">
            <div
                class="progress-bar bg-{{ $expense->status->color() }}"
                style="width: {{ $expense->status->progress() }}%"
                aria-valuenow="{{ $expense->status->progress() }}"
                role="progressbar" aria-valuemin="0" aria-valuemax="100"
            ></div>
        </div>

        <hr>

        @can('changeStatus', $expense)
            <div class="d-flex justify-content-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" wire:model="expense.is_approved_for_payment">
                </div>
            </div>
        @endcan

        @if($expense->is_approved_for_payment)
            <h3 class="text-center mt-3"><i class="fas fa-check text-success"></i> {{ __('Approved for payment') }}</h3>
        @else
            <h3 class="text-center mt-3"><i class="fas fa-times text-danger"></i> {{ __('Not approved for payment') }}</h3>
        @endif

        <hr>

        @if($expense->sent_to_clearfacts_at)
            <p class="text-success text-center mb-2">
                {{ __('Uploaded to clearfacts at :date', ['date' => $expense->sent_to_clearfacts_at?->format('d M Y H:i')]) }}
            </p>
        @endif

        @if($expense->supplier->is_disabled_for_clearfacts)
            <p class="text-center text-muted">
                {{ __('The supplier of this expense has Clearfacts disabled.') }}
            </p>
        @endif

        <div class="d-flex justify-content-center">
            <a
                href="#confirmUploadClearfactsModal"
                data-bs-toggle="modal"
                class="btn btn-link link-primary"
            >
                {{ __('Upload to clearfacts') }}
            </a>
        </div>

        <div class="d-flex justify-content-center">
            <form method="POST" action="{{ action(\App\Http\Controllers\Ampp\Expenses\ConfirmExpenseExistsInClearfactsController::class, $expense) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-secondary mt-2">
                    {{ __('This expense already exists in Clearfacts') }}
                </button>
            </form>
        </div>
    </div>
</div>

<x-push name="modals">
    <x-ui.confirmation-modal id="confirmDeleteModal">
        <x-slot name="content">
            {{ __('Are you sure you want to delete this expense? This action can not be reverted!') }}
        </x-slot>

        <x-slot name="actions">
            <x-forms.form :action="action(\App\Http\Controllers\Ampp\Expenses\DestroyExpenseController::class, $expense)" method="delete">
                <button class="btn btn-danger">{{ __('Delete') }}</button>
            </x-forms.form>
        </x-slot>
    </x-ui.confirmation-modal>

    <x-ui.confirmation-modal id="confirmUploadClearfactsModal">
        <x-slot name="content">
            {{ __('Are you sure you want to upload this expense to Clearfacts? This action can not be reverted!') }}
        </x-slot>

        <x-slot name="actions">
            <a
                href="{{ action(\App\Http\Controllers\Ampp\Expenses\UploadExpenseToClearfactsController::class, $expense) }}"
                class="btn btn-primary"
            >
                {{ __('Yes, upload to Clearfacts') }}
            </a>
        </x-slot>
    </x-ui.confirmation-modal>
</x-push>
