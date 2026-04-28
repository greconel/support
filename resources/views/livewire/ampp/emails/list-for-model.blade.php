<div>
    <header class="d-flex justify-content-end mb-4 gap-4">
        <a href="#" class="link-secondary text-decoration-none" wire:click.prevent="refreshMails">
            <i class="fas fa-sync-alt me-1" wire:loading.class="fa-spin" wire:target="refreshMails"></i>
            {{ __('Refresh') }}
        </a>

        @if($createModal)
            <a href="#{{ $createModal }}" class="link-primary text-decoration-none" data-bs-toggle="modal">
                <i class="fas fa-plus"></i>
                {{ __('Send new e-mail') }}
            </a>
        @endif
    </header>

    <div class="overflow-auto" style="max-height: 400px">
        <ul class="list-group list-group-flush">
            @forelse($emails->sortByDesc('created_at') as $email)
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div class="pe-3 d-block text-truncate">
                            <a href="#" class="stretched-link" wire:click.prevent="$dispatch('previewEmail', { id: {{ $email->id }} })">{{ $email->subject }}</a>
                        </div>

                        <div class="d-flex align-center">
                            <div>
                                <span class="small text-muted me-2">{{ $email->created_at->format('d/m/Y H:i') }}</span>
                            </div>

                            <div>
                                @if($email->status == 'sent')
                                    <span class="badge rounded-pill bg-success">{{ $email->status }}</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary">{{ $email->status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <p class="text-center text-muted">{{ __('Start sending some mails!') }}</p>
            @endforelse
        </ul>
    </div>
</div>
