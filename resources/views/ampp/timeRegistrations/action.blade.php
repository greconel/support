<div class="btn-group">
    <button class="btn btn-info btn-sm" type="button" x-data @click="Livewire.dispatch('editTimeRegistration', { id: {{ $id}} })">
        {{ __('Edit') }}
    </button>
</div>
