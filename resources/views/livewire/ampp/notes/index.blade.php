<div class="overflow-auto p-4" style="max-height: 500px">
    @forelse($notes as $note)
        <div>
            <div class="d-flex justify-content-between">
                <p class="lead fw-bold mb-1">{{ $note->title }}</p>
                <button class="btn btn-sm btn-link" wire:click="edit({{ $note->id }})"><i class="fas fa-edit"></i></button>
            </div>

            <p class="small text-muted">
                {{ __('Created by :user at :time', ['user' => $note->user->name, 'time' => $note->created_at->format('l d F H:i') ]) }}
            </p>

            <x-ui.quill-display :content="$note->description" />

            <hr>
        </div>
    @empty
        <p class="text-center text-muted">{{ __('Nothing here..') }}</p>
    @endforelse
</div>

