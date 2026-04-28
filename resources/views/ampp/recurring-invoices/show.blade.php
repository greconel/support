<x-layouts.ampp :title="$recurringInvoice->name" :breadcrumbs="Breadcrumbs::render('showRecurringInvoice', $recurringInvoice)">
    <div class="container">
        <x-ui.page-title>
            {{ $recurringInvoice->name }}

            @if($recurringInvoice->is_active)
                <span class="badge rounded-pill bg-success ms-2">{{ __('Active') }}</span>
            @else
                <span class="badge rounded-pill bg-secondary ms-2">{{ __('Inactive') }}</span>
            @endif
        </x-ui.page-title>

        <div class="row justify-content-center">
            <div class="col-lg-5">
                {{-- Details --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ __('Details') }}

                        <a href="{{ action(\App\Http\Controllers\Ampp\RecurringInvoices\EditRecurringInvoiceController::class, $recurringInvoice) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> {{ __('Edit') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="fw-bold">{{ __('Client') }}</td>
                                <td>
                                    <a href="{{ action(\App\Http\Controllers\Ampp\Clients\ShowClientController::class, $recurringInvoice->client) }}">
                                        {{ $recurringInvoice->client->full_name_with_company }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">{{ __('Period') }}</td>
                                <td>{{ $recurringInvoice->period->label() }}</td>
                            </tr>
                            @if($recurringInvoice->po_number)
                                <tr>
                                    <td class="fw-bold">{{ __('PO Number') }}</td>
                                    <td>{{ $recurringInvoice->po_number }}</td>
                                </tr>
                            @endif
                            @if($recurringInvoice->notes)
                                <tr>
                                    <td class="fw-bold">{{ __('Notes') }}</td>
                                    <td>{{ $recurringInvoice->notes }}</td>
                                </tr>
                            @endif
                            @if($recurringInvoice->amount)
                                <tr>
                                    <td class="fw-bold">{{ __('Amount excl. VAT') }}</td>
                                    <td>{{ $recurringInvoice->amount_formatted }}</td>
                                </tr>
                            @endif
                            @if($recurringInvoice->amount_with_vat)
                                <tr>
                                    <td class="fw-bold">{{ __('Amount incl. VAT') }}</td>
                                    <td>{{ $recurringInvoice->amount_with_vat_formatted }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Danger zone --}}
                <div class="card border-danger mt-3">
                    <div class="card-header text-danger">{{ __('Danger zone') }}</div>
                    <div class="card-body">
                        <form action="{{ action(\App\Http\Controllers\Ampp\RecurringInvoices\DestroyRecurringInvoiceController::class, $recurringInvoice) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('Are you sure?') }}')">
                                <i class="fas fa-trash-alt"></i> {{ __('Delete recurring invoice') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                {{-- Billing lines overview --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ __('Billing lines') }}

                        <a href="{{ action(\App\Http\Controllers\Ampp\RecurringInvoices\EditRecurringInvoiceLinesController::class, $recurringInvoice) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> {{ __('Edit lines') }}
                        </a>
                    </div>

                    <div class="card-body">
                        @if($recurringInvoice->billingLines->count() > 0)
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Subtotal') }}</th>
                                        <th>{{ __('VAT') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recurringInvoice->billingLines->sortBy('order') as $line)
                                        <tr>
                                            <td>{{ $line->order }}</td>
                                            @if($line->type === 'header')
                                                <td colspan="5" class="fw-bold">{{ $line->text }}</td>
                                            @else
                                                <td>
                                                    {{ $line->text }}
                                                    @if($line->product_id)
                                                        <i class="fas fa-link text-muted small" title="{{ __('Linked to product') }}"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $line->price_formatted }}</td>
                                                <td>{{ $line->amount_formatted }}</td>
                                                <td>{{ $line->sub_total_formatted }}</td>
                                                <td>{{ $line->vat_formatted }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted mb-0">{{ __('No billing lines yet.') }} <a href="{{ action(\App\Http\Controllers\Ampp\RecurringInvoices\EditRecurringInvoiceLinesController::class, $recurringInvoice) }}">{{ __('Add lines') }}</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.ampp>
