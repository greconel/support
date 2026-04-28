<div
    x-data="{
        modal: new bootstrap.Modal($refs.modal),
        modalPreview: new bootstrap.Modal($refs.modalPreview),
        toSelect: null,
        ccSelect: null,
        bccSelect: null,
        content: $wire.entangle('content')
     }"
    x-init="
        tinymce.init({
            target: $refs.editor,
            ...TINYMCE_FULL_CONFIG,
            setup: thisEditor => {
                thisEditor.on('blur', () => {
                    $wire.set('content', thisEditor.getContent());
                })
            }
        });

        this.toSelect = new TomSelect($refs.to, {
            allowEmptyOption: false,
            hidePlaceholder: true,
            create:true
        });

        this.ccSelect = new TomSelect($refs.cc, {
            allowEmptyOption: true,
            hidePlaceholder: true,
            create:true
        });

        this.bccSelect = new TomSelect($refs.bcc, {
            allowEmptyOption: true,
            hidePlaceholder: true,
            create:true
        });

        $wire.on('close', () => {
            modal.hide();
            tinymce.get($refs.editor.id).setContent(content);
            this.toSelect.clear();
            this.ccSelect.clear();
            this.bccSelect.clear();
        });

        $wire.on('openPreview', () => modalPreview.show());

        $refs.modal.addEventListener('show.bs.modal', () => $wire.refresh());
    "
>
    <div
        class="modal fade"
        id="invoiceEmailModal"
        aria-labelledby="invoiceEmailModalLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        wire:ignore.self x-ref="modal"
    >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceEmailModalLabel">{{ __('Send new email') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if($invoice->client?->peppol_only)
                        <div class="alert alert-info mb-3" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('This client wants to receive invoices only via Peppol. Use the Peppol section to send.') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="send" id="invoiceEmailForm">
                        <div class="row">
                            <div class="col">
                                <x-forms.label for="to[]">{{ __('To') }}</x-forms.label>

                                <div wire:ignore>
                                    <select name="to[]" required x-ref="to" multiple wire:model="to">
                                        @foreach($contacts as $email => $name)
                                            <option value="{{ $email }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <x-forms.error-message for="to" />
                            </div>
                        </div>

                        <a class="small" data-bs-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
                            {{ __('Cc and bcc') }}
                        </a>

                        <div class="collapse pt-3" id="collapse" wire:ignore.self>
                            <div class="row">
                                <div class="col mb-3">
                                    <x-forms.label for="cc[]">{{ __('Cc') }}</x-forms.label>

                                    <div wire:ignore>
                                        <select name="to[]" x-ref="cc" multiple wire:model="cc">
                                            @foreach($contacts as $email => $name)
                                                <option value="{{ $email }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <x-forms.error-message for="cc" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mb-3">
                                    <x-forms.label for="bcc[]">{{ __('Bcc') }}</x-forms.label>

                                    <div wire:ignore>
                                        <select name="to[]" x-ref="bcc" multiple wire:model="bcc">
                                            @foreach($contacts as $email => $name)
                                                <option value="{{ $email }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <x-forms.error-message for="bcc" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mt-3 mb-4">
                                <x-forms.label for="subject">{{ __('Subject') }}</x-forms.label>
                                <x-forms.input name="subject" required wire:model="subject" />
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col">
                                <div wire:ignore>
                                    <textarea x-ref="editor" id="invoiceEmailEditor">{!! $content !!}</textarea>
                                </div>

                                <x-forms.error-message for="content" />
                            </div>
                        </div>

                        <x-forms.label for="">{{ __('Attachments') }}</x-forms.label>

                        <div class="d-flex">
                            <div class="mb-3 me-4">
                                <x-forms.checkbox name="attachments[]" id="attachment_invoice" value="invoice" wire:model.lazy="attachments">
                                    {{ __('Invoice as PDF') }}
                                </x-forms.checkbox>
                            </div>

                            <div class="mb-3 me-4">
                                <x-forms.checkbox name="attachments[]" id="attachment_invoice_reminder" value="invoice_reminder" wire:model.lazy="attachments">
                                    {{ __('Invoice as PDF [reminder]') }}
                                </x-forms.checkbox>
                            </div>

                            @if($invoice->client->validated_vat && $invoice->sent_to_recommand_at !== null)
                                <div class="mb-3 me-4">
                                    <x-forms.checkbox name="attachments[]" id="attachment_invoice_peppol" value="invoice_peppol" wire:model.lazy="attachments">
                                        {{ __('Send via Peppol') }} <span class="text-info">{{ __('Already sent on :date', ['date' => $invoice->sent_to_recommand_at->format('d/m/Y H:i')]) }}</span>
                                    </x-forms.checkbox>
                                </div>
                            @elseif($invoice->client->validated_vat && $peppolStatus['canReceive'] && empty($peppolValidationErrors))
                                <div class="mb-3 me-4">
                                    <x-forms.checkbox name="attachments[]" id="attachment_invoice_peppol" value="invoice_peppol" wire:model.lazy="attachments">
                                        {{ __('Send via Peppol') }} <span class="badge rounded-pill bg-success">{{ __('Recipient can receive via Peppol') }} ✓</span>
                                    </x-forms.checkbox>
                                </div>
                            @elseif($invoice->client->validated_vat && !$peppolStatus['canReceive'])
                                <div class="mb-3 me-4">
                                    <x-forms.checkbox name="attachments[]" id="attachment_invoice_peppol" value="invoice_peppol" disabled>
                                        {{ __('Send via Peppol') }} <span class="text-muted">{{ $peppolStatus['reason'] ?? __('Recipient cannot receive via Peppol') }}</span>
                                    </x-forms.checkbox>
                                </div>
                            @elseif($invoice->client->validated_vat && $peppolStatus['canReceive'] && !empty($peppolValidationErrors))
                                <div class="mb-3 me-4">
                                    <x-forms.checkbox name="attachments[]" id="attachment_invoice_peppol" value="invoice_peppol" disabled>
                                        {{ __('Send via Peppol') }} <span class="text-warning">⚠ {{ __('Validation errors') }}</span>
                                    </x-forms.checkbox>
                                </div>
                            @endif
                        </div>

                        @if($invoice->client->validated_vat && !empty($peppolValidationErrors))
                            <div class="alert alert-warning" role="alert">
                                <strong>{{ __('Peppol Validation Errors') }}:</strong>
                                <p class="mb-2 mt-2">{{ __('The invoice cannot be sent via Peppol due to the following validation errors') }}:</p>
                                <ul class="mb-0" style="font-family: monospace; font-size: 0.9em;">
                                    @foreach($peppolValidationErrors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col">
                                @foreach($invoice->getMedia('files') as $file)
                                    <x-forms.checkbox name="attachments[]" id="attachment_{{ $file->id }}" value="{{ $file->id }}" wire:model.lazy="attachments">
                                        {{ $file->name }}
                                    </x-forms.checkbox>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

                    <div>
                        <button type="button" class="btn btn-info" wire:click="preview" wire:loading.attr="disabled" wire:target="preview">
                            <span>{{ __('Preview') }}</span>

                            <div wire:loading wire:target="preview">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>

                        <button type="submit" class="btn btn-primary" form="invoiceEmailForm" wire:loading.attr="disabled" wire:target="send">
                            <span>{{ __('Send') }}</span>

                            <div wire:loading wire:target="send">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Preview modal --}}
    <div
        class="modal fade"
        id="invoiceEmailPreviewModal"
        aria-labelledby="invoiceEmailPreviewModalLabel"
        aria-hidden="true"
        wire:ignore.self x-ref="modalPreview"
    >
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceEmailPreviewModalLabel">{{ __('Preview') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if(!empty($previewMail))
                        <iframe srcdoc="{!! htmlspecialchars($previewMail, ENT_QUOTES) !!}" class="d-block mx-auto" style="width: 100%; height: 100%; max-width: 1000px"></iframe>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
