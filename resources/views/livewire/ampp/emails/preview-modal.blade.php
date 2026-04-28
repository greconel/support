<div
    class="modal fade"
    x-data="{ modal: new bootstrap.Modal($refs.modal) }"
    x-init="$wire.on('open', () => modal.show());"
    x-ref="modal"
    wire:ignore.self
>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mailPreviewModalLabel">{{ __('Preview') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                @if($previewEmail)
                    <p>
                        <span>{{ __('To') }}: </span>
                        {{ implode(',', $previewEmail?->to) }}
                    </p>

                    @if($previewEmail?->cc)
                        <p>
                            <span>{{ __('Cc') }}: </span>
                            {{ implode(',', $previewEmail?->cc) }}
                        </p>
                    @endif

                    @if($previewEmail?->bcc)
                        <p>
                            <span>{{ __('Bcc') }}: </span>
                            {{ implode(',', $previewEmail?->bcc) }}
                        </p>
                    @endif

                    <p>
                        <span>{{ __('Subject') }}: </span>
                        {{ $previewEmail?->subject }}
                    </p>

                    @if(!$previewEmail->content_full)
                        <div class="alert alert-warning" role="alert">
                            {{ __('This mail isn\'t saved when it was sent, the preview might change according to the template.') }}
                        </div>
                    @endif

                    <iframe srcdoc="{!! htmlspecialchars($previewEmail->content_full ?? (new $previewEmail->mailable($previewEmail))->render(), ENT_QUOTES) !!}" class="d-block mx-auto" style="width: 100%; min-height: 500px; max-width: 1000px"></iframe>

                    <p class="text-decoration-underline mt-4 mb-3">{{ __('Attachments') }}</p>

                    @foreach($previewEmail?->getMedia('attachments') as $attachment)
                        <div>
                            <a href="{{ action(\App\Http\Controllers\Media\DownloadMediaController::class, $attachment) }}">{{ $attachment->name }}</a>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
