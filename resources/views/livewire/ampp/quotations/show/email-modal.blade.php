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
    <div class="modal fade" id="quotationEmailModal" aria-labelledby="quotationEmailModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false"
         wire:ignore.self x-ref="modal"
    >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quotationEmailModalLabel">{{ __('Send a new email') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="send" id="quotationEmailForm">
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
                                    <textarea x-ref="editor" id="quotationEmailEditor">{!! $content !!}</textarea>
                                </div>

                                <x-forms.error-message for="content" />
                            </div>
                        </div>

                        <x-forms.label for="">{{ __('Attachments') }}</x-forms.label>

                        <div class="row">
                            <div class="col mb-3">
                                <x-forms.checkbox name="attachments[]" id="attachment_quotation" value="quotation" wire:model.lazy="attachments">
                                    {{ __('PDF') }}
                                </x-forms.checkbox>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                @foreach($quotation->getMedia('files') as $file)
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

                        <button type="submit" class="btn btn-primary" form="quotationEmailForm" wire:loading.attr="disabled" wire:target="send">
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
        id="quotationEmailPreviewModal"
        aria-labelledby="quotationEmailPreviewModalLabel"
        aria-hidden="true"
        wire:ignore.self
        x-ref="modalPreview"
    >
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quotationEmailPreviewModalLabel">{{ __('Preview') }}</h5>

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
