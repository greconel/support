<label
    x-data="{
        tableId: {{ json_encode($tableId) }},
        tableUrl: {{ json_encode(request()->path()) }},
        value: '',
        isFocused: $($refs.input).is(':focus'),

        get table() { return $(`#${this.tableId}`).DataTable() },

        get stateValue() {
            const stateValue = JSON.parse(localStorage.getItem(`DataTables_${this.tableId}_/${this.tableUrl}`));

            if (! stateValue){
                return null;
            }

            this.value = stateValue.search.search;
        },

        get showKbd() {
            if (this.value.length > 0){
                return false;
            }

            return !this.isFocused;
        }
    }"
    class="w-100 mb-3 mb-md-0 position-relative"
    style="max-width: 40rem"
>
    <input
        name="{{ $tableId }}-search"
        type="search"
        autofocus
        x-init="this.value = stateValue"
        @input="table.search($event.target.value).draw()"
        @keydown.slash.window.prevent="$refs.input.focus()"
        @focusin="isFocused = true"
        @focusout="isFocused = false"
        placeholder="{{ $placeholder }}"
        x-ref="input"
        x-model="value"
        {{ $attributes->class(['py-2 form-control']) }}
    />

    <span class="position-absolute end-0 top-50 translate-middle-y me-2" x-show="showKbd">
        <kbd>/</kbd>
    </span>
</label>
