<div class="overflow-auto" style="max-height: 500px">
    @forelse($todos as $index => $todo)
        <x-todos.todo :todo="$todo" wire:model="model.todos.{{ $index }}.finished" />
    @empty
        <p class="text-muted text-center">{{ __('Yay! Nothing to do over here!') }}</p>
    @endforelse
</div>
