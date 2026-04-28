<x-push name="styles">
    <style>
        .handle {
            cursor: grab;
        }
    </style>
</x-push>

<div
    x-data="{
        descriptionModal: new bootstrap.Modal($refs.descriptionModal),
        discountModal: new bootstrap.Modal($refs.discountModal),
        pdfModal: new bootstrap.Modal($refs.pdfModal),
        reorderRows(){
            let counter = 1;

            $refs.tableBody.querySelectorAll(':scope > tr').forEach((e) => {
                const orderInput = e.querySelector(`input[id^='order_']`);
                const inputIndex = $(orderInput).data('index');

                $wire.set(`lines.${inputIndex}.order` , counter);
                counter++;
            });
        }
    }"

    x-init="
        $('#tableBody').sortable({
            handle: '.handle',
            animation: 150,
            ghostClass: 'bg-light',
            stop: () => reorderRows()
        });

        $wire.on('openDescriptionModal', () => descriptionModal.show())
        $wire.on('closeDescriptionModal', () => descriptionModal.hide())
        $wire.on('openDiscountModal', () => discountModal.show())
        $wire.on('closeDiscountModal', () => discountModal.hide())
        $wire.on('openPdfModal', () => pdfModal.show())
    "
>
    <div class="card rounded-lg shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="fw-bolder fs-4">{{ __('Billing lines') }}</span>

                @if(!$saved)
                    <span class="text-danger ms-4">{{ __('There are changes that aren\'t saved yet!') }}</span>
                @endif
            </div>

            <div class="d-flex justify-content-end align-items-center">
                <a href="#" class="link-primary me-4" wire:click.prevent="generatePdf" wire:loading.remove>
                    <i class="far fa-file-pdf"></i> {{ __('Open PDF') }}
                </a>

                <div wire:loading wire:target="generatePdf">
                    <i class="far fa-file-pdf text-primary"></i>

                    <div class="spinner-border spinner-border-sm text-primary me-4" role="status">
                        <span class="visually-hidden">{{ __('Generating PDF') }}</span>
                    </div>
                </div>

                <a href="#" class="link-primary" wire:click.prevent="resetCache">
                    <i class="fas fa-redo-alt"></i> {{ __('Reset') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save" id="billingLinesForm">
                <table class="table table-sm table-borderless table-responsive-sm">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Price excl. VAT') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Subtotal with discount') }}</th>
                        <th>{{ __('VAT') }}</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody id="tableBody" x-ref="tableBody">
                    @foreach(collect($this->lines)->sortBy('order')->toArray() as $index => $line)
                        <tr>
                            {{-- Order --}}
                            <td class="align-middle handle" style="width: 3%;">
                                <input type="hidden" wire:model.lazy="lines.{{ $index }}.order" id="order_{{ $index }}"
                                       data-index="{{ $index }}">
                                {{ $lines[$index]['order'] }}
                            </td>

                            @if($line['type'] == 'header')
                                {{-- Text --}}
                                <td colspan="5">
                                    <x-forms.input
                                        name="lines[{{ $index }}][text]"
                                        required
                                        wire:model.lazy="lines.{{ $index }}.text"
                                    />
                                </td>

                                {{-- Row buttons --}}
                                <td style="width: 5%;">
                                    <div class="d-flex ps-1">
                                        <a href="#" class="nav-link text-danger px-0 mx-1"
                                           wire:click.prevent="deleteLine('{{ $index }}')"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            @else
                                {{-- Text --}}
                                <td
                                    x-data="{
                                        get products() {
                                            return $wire.products.map(p => {
                                                return {
                                                    label: p['name'],
                                                    price: p['price'].toString(),
                                                    product_id: p['id']
                                                }
                                            })
                                        }
                                    }"
                                    x-init="
                                        $($refs.inputText).autocomplete({
                                            source: products,
                                            select: (event, ui) => {
                                                const index = $(event.target).closest('tr').find('input[data-index]').attr('data-index');
                                                $wire.set(`lines.${index}.price`, ui.item.price);
                                                $wire.set(`lines.${index}.product_id`, ui.item.product_id);
                                            }
                                        });
                                    "
                                    style="width: 45%;"
                                >
                                    <x-forms.input
                                        name="lines[{{ $index }}][text]"
                                        :error-for="'lines.' . $index . '.text'"
                                        required
                                        x-ref="inputText"
                                        wire:model.lazy="lines.{{ $index }}.text"
                                    />
                                </td>

                                {{-- Price / item --}}
                                <td style="width: 12%;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">€</span>
                                        </div>

                                        <x-forms.input name="lines[{{ $index }}][price]" type="number"
                                                       :error-for="'lines.' . $index . '.price'" step="0.01" required
                                                       wire:model.lazy="lines.{{ $index }}.price"/>
                                    </div>
                                </td>

                                {{-- Amount --}}
                                <td style="width: 10%;">
                                    <x-forms.input name="lines[{{ $index }}][amount]" type="number"
                                                   :error-for="'lines.' . $index . '.amount'" step="0.01" required
                                                   wire:model.lazy="lines.{{ $index }}.amount"/>
                                </td>

                                {{-- Total row price --}}
                                <td style="width: 15%;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">€</span>
                                        </div>

                                        <x-forms.input name="lines[{{ $index }}][subtotal]" type="number"
                                                       :error-for="'lines.' . $index . '.subtotal'" step="0.01" required
                                                       readonly wire:model.lazy="lines.{{ $index }}.subtotal"/>

                                        <div class="input-group-append">
                                            <span
                                                class="input-group-text">- {{ floatval($line['discount']) ?? 0 }}%</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Vat --}}
                                <td style="width: 10%;">
                                    <select name="lines[{{ $index }}][vat]" class="form-select" required
                                            wire:model.lazy="lines.{{ $index }}.vat">
                                        @foreach(\App\Models\BillingLines::getVatValues() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>

                                    <x-forms.error-message :for="'lines.' . $index . '.vat'"/>
                                </td>

                                {{-- Row buttons --}}
                                <td style="width: 5%;">
                                    <div class="d-flex ps-1">
                                        <a href="#" wire:click.prevent="$dispatch('openDiscountModal', '{{ $index }}')"
                                           class="nav-link px-0 mx-1 @if($lines[$index]['discount'] && $lines[$index]['discount'] != 0) text-info @else text-secondary @endif"
                                        >
                                            <i class="fas fa-tags"></i>
                                        </a>

                                        <a href="#" wire:click.prevent="$dispatch('openDescriptionModal', '{{ $index }}')"
                                           class="nav-link px-0 mx-1 @if($lines[$index]['description']) text-info @else text-secondary @endif"
                                        >
                                            <i class="fas fa-align-left"></i>
                                        </a>

                                        <a href="#" wire:click.prevent="deleteLine('{{ $index }}')"
                                           class="nav-link text-danger px-0 mx-1"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>

            <div class="text-end">
                {{ __('Total excl. VAT') }}: € <span id="total">{{ $totalPrice }}</span>
            </div>

            <div class="d-flex justify-content-center">
                <button class="btn btn-success me-2" wire:click="addLine('text')"><i class="fas fa-plus"></i></button>
                <button class="btn btn-info" wire:click="addLine('header')"><i class="fas fa-heading"></i></button>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn btn-primary" form="billingLinesForm" wire:loading.attr="disabled" wire:target="save">
            <span>{{ __('Save') }}</span>

            <div wire:loading wire:target="save">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span class="visually-hidden">Loading...</span>
            </div>
        </button>
    </div>

    {{-- MODALS --}}

    <!-- Description modal -->
    <div class="modal fade" aria-labelledby="descriptionModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" x-ref="descriptionModal" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Description') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <textarea name="description" rows="5" class="form-control" wire:model.lazy="description"></textarea>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-success" wire:click="saveDescription">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Discount modal -->
    <div class="modal fade" aria-labelledby="discountModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" x-ref="discountModal" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">{{ __('Discount') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th class="text-uppercase">{{ __('Unit price excl. VAT') }}</th>
                            <th></th>
                            <th class="text-uppercase">{{ __('Discount') }}</th>
                            <th></th>
                            <th class="text-uppercase">{{ __('Price after discount') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>
                                <x-forms.input type="number" name="disc_product_price" step="0.01" readonly wire:model.lazy="discProductPrice" />
                            </td>

                            <td class="align-middle">
                                <i class="fas fa-minus-square fa-lg text-primary"></i>
                            </td>

                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">%</span>
                                    </div>

                                    <x-forms.input type="number" name="disc_discount" step="0.01" wire:model.lazy="discPercentage" />
                                </div>
                            </td>

                            <td class="align-middle">
                                <i class="fas fa-equals fa-lg text-primary"></i>
                            </td>

                            <td>
                                <x-forms.input type="number" name="disc_price" step="0.01" readonly wire:model.lazy="discPrice" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-success" wire:click="saveDiscount">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Pdf modal --}}
    <div class="modal fade" aria-labelledby="pdfModalLabel" aria-hidden="true" x-ref="pdfModal" wire:ignore.self>
        <div class="modal-xl modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">{{ __('PDF preview') }}</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <iframe src="data:application/pdf;base64,{{ $pdf }}" x-ref="pdf" type="application/pdf" style="min-height: 70vh; min-width: 100%;"></iframe>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

