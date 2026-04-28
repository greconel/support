@props(['checkbox', 'todo'])

@php /** @var \App\Models\Todo $todo */ @endphp

<div class="d-flex border-bottom pb-3 mb-3">
    <div class="me-2">
        <input
            class="todo-checkbox"
            type="checkbox"
            value=""
            id="todo_{{ $todo->id }}"
            {{ $attributes->wire('model') }}
        >
    </div>

    <div>
        <header>
            <h4
                wire:click="edit({{ $todo->id }})"
                @class(['cursor-pointer', 'text-decoration-line-through text-gray-600' => $todo->finished])
            >
                {{ $todo->title }}
            </h4>
        </header>

        @if($todo->description)
            <main class="text-muted mb-2">
                <x-ui.quill-display :content="$todo->description" />
            </main>
        @endif

        @if($todo->end_date)
            <footer>
                @if(!$todo->finished && $todo->end_date < now())
                    <i class="fas fa-exclamation-circle text-danger me-1"></i>
                @else
                    <i class="far fa-calendar-alt text-blue-700 me-1"></i>
                @endif

                @if($todo->end_date?->isToday())
                    {{ __('Today') }} {{ $todo->end_date->format('H:i') }}
                @elseif($todo->end_date?->isTomorrow())
                    {{ __('Tomorrow') }} {{ $todo->end_date->format('H:i') }}
                @elseif($todo->end_date?->isYesterday())
                    {{ __('Yesterday') }} {{ $todo->end_date->format('H:i') }}
                @else
                    {{ $todo->end_date?->format('d/m/Y H:i') }}
                @endif
            </footer>
        @endif
    </div>
</div>
