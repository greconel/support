@php
    /** @var \App\Models\DealColumn $column */
@endphp

<div class="h-100">
    <div class="board" wire:sortable="updateColumnOrder" wire:sortable-group="updateDealOrder">
        @foreach($columns->sortBy('order') as $column)
            <div class="board-column" wire:key="column-{{ $column->id }}" wire:sortable.item="{{ $column->id }}">
                <div class="board-column-header">
                    <div class="d-flex justify-content-between" x-data="{ rename: false }">
                        <div class="flex-grow-1" wire:sortable.handle>
                            <div x-show="! rename" x-transition>
                                {{ $column->name }}
                            </div>

                            <div x-show="rename" x-transition @click.outside="rename = false">
                                <form wire:submit.prevent="editColumn({{ $column->id }})" @submit.prevent="rename = false">
                                    <x-forms.input
                                        name="column_name"
                                        required
                                        placeholder="{{ $column->name }}"
                                        x-ref="input"
                                        x-intersect:enter="$refs.input.focus()"
                                        class="mb-3"
                                        wire:model="columnName"
                                    />

                                    <button class="btn btn-primary">{{ __('Edit column') }}</button>
                                </form>
                            </div>
                        </div>

                        <div class="dropdown">
                            <a href="#" class="text-decoration-none link-secondary ps-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item" @click="rename = true">{{ __('Rename') }}</button>
                                </li>

                                <li>
                                    <button class="dropdown-item" wire:click="sortBy({{ $column->id }}, 'due_date', 'desc')">
                                        {{ __('Sort by due date (descending)') }}
                                    </button>
                                </li>

                                <li>
                                    <button class="dropdown-item" wire:click="sortBy({{ $column->id }}, 'due_date', 'asc')">
                                        {{ __('Sort by due date (ascending)') }}
                                    </button>
                                </li>

                                <li>
                                    <button class="dropdown-item" data-column-id="{{ $column->id }}" data-bs-toggle="modal" data-bs-target="#confirmDeleteColumnModal">
                                        {{ __('Delete') }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="board-column-content" wire:sortable-group.item-group="{{ $column->id }}">
                    <div>
                        € {{ $this->getPipelineRevenueForColumn($column) }}
                    </div>

                    @foreach($column->deals->sortBy('order') as $deal)
                        <x-ampp.deals.card :deal="$deal" />
                    @endforeach
                </div>

                <div class="board-column-footer">
                    <a href="#" class="link-secondary text-decoration-none" wire:click.prevent="$dispatch('createDeal', { columnId: {{ $column->id }} })">
                        <i class="fas fa-plus"></i>
                        {{ __('Add a lead') }}
                    </a>
                </div>
            </div>
        @endforeach

        <div
            class="board-column-add"
            x-data="{ show: false }"
            @click="show = true"
            :class="{ 'active': show }"
        >
            <div class="text-center" x-show="!show" x-transition>
                {{ __('Add new column') }}
            </div>

            <div x-show="show" x-transition @click.outside="show = false">
                <form wire:submit.prevent="addColumn">
                    <x-forms.input
                        name="column_name"
                        required
                        placeholder="{{ __('Column name') }}"
                        x-ref="input"
                        x-intersect:enter="$refs.input.focus()"
                        class="mb-3"
                        wire:model="columnName"
                    />

                    <button class="btn btn-primary">{{ __('Add column') }}</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Column delete modal --}}
    <x-ui.confirmation-modal
        id="confirmDeleteColumnModal"
        wire:ignore.self
        x-data="{
            columnDeleteModal: null,
            columnToDelete: null
        }"
        x-init="
            columnDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteColumnModal'));

            document.getElementById('confirmDeleteColumnModal').addEventListener('show.bs.modal', function(e) {
                columnToDelete = e.relatedTarget.dataset.columnId;
            });

            $wire.on('closeColumnDeleteModal', () => columnDeleteModal.hide());
        "
    >
        <x-slot name="content">
            {{ __('Are you sure you want to delete this column? All of the deals that are in this column will be deleted as well. This action can not be reverted!') }}
        </x-slot>

        <x-slot name="actions">
            <button class="btn btn-danger" @click="$wire.call('deleteColumn', columnToDelete)">{{ __('Delete') }}</button>
        </x-slot>
    </x-ui.confirmation-modal>
</div>

<x-push name="scripts">
    <script src="{{ asset('vendor/livewire-sortable/board.js') }}"></script>
</x-push>

