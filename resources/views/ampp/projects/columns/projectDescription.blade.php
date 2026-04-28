<a
    x-data="{ description: {{ json_encode(\Illuminate\Support\Str::limit(strip_tags($project->description), 50)) }} }"
    @click.prevent="Livewire.dispatch('editDescription', { id: '{{ $project->id }}' })"
    wire:key="description-{{ $project->id }}"
    :class="{'link-gray-600': description.length !== 0, 'text-gray-500 text-sm': description.length === 0}"
    @updated-description-for-{{ $project->id }}.window="description = $event.detail.value ?? ''"
>
    <div x-html="description"></div>

    <div x-show="description.length === 0">
        <span>Fill description</span>
    </div>
</a>
