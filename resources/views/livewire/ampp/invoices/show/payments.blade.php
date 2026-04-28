<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bolder fs-4">{{ __('Payments') }}</span>

        <a href="#invoicePaymentModal" class="btn btn-sm btn-primary" data-bs-toggle="modal">
            <i class="fas fa-plus me-1"></i> {{ __('Add payment') }}
        </a>
    </div>

    <div class="card-body">
        <div class="row mb-3 text-center">
            <div class="col-md-4">
                <div>
                    <span class="text-muted">{{ __('Invoice total') }}</span>
                    <br>
                    <span class="fw-bold">{{ $invoice->amount_with_vat_formatted }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div>
                    <span class="text-muted">{{ __('Total paid') }}</span>
                    <br>
                    <span class="fw-bold text-success">{{ $invoice->total_paid_formatted }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div>
                    <span class="text-muted">{{ __('Remaining balance') }}</span>
                    <br>
                    <span class="fw-bold {{ $invoice->is_fully_paid ? 'text-success' : 'text-warning' }}">
                        {{ $invoice->remaining_balance_formatted }}
                    </span>
                </div>
            </div>
        </div>

        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Method') }}</th>
                            <th>{{ __('Notes') }}</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->paid_at->format('d/m/Y') }}</td>
                                <td class="fw-bold">{{ $payment->amount_formatted }}</td>
                                <td>
                                    @if($payment->payment_method)
                                        {{ $payment->payment_method->label() }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->notes)
                                        <span title="{{ $payment->notes }}">{{ Str::limit($payment->notes, 30) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="#invoicePaymentModal"
                                       class="btn btn-sm btn-link p-0 me-2"
                                       data-bs-toggle="modal"
                                       wire:click="$dispatch('editPayment', { paymentId: {{ $payment->id }} })"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-link p-0 text-danger"
                                        wire:click="deletePayment({{ $payment->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this payment?') }}"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted py-3">
                {{ __('No payments recorded yet.') }}
            </div>
        @endif
    </div>
</div>
