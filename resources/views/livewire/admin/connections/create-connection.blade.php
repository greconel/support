<div class="d-flex flex-column justify-content-center align-items-center">
    <label for="icon" class="position-relative overflow-hidden border" style="cursor: pointer">
        <img
            src="{{ $photo ? $photo->temporaryUrl() : null}}"
{{--            alt="icon"--}}
            class="img-fluid"
            style="object-fit: cover; min-height: 150px; min-width: 150px;"
        >

        <div
            class="position-absolute start-50 translate-middle-x small w-100 d-flex justify-content-center align-items-center text-white"
            style="top: 70%; height: 30%; white-space: nowrap; background-color:rgba(0, 0, 0, 0.2);"
        >
            {{ __('Change') }}
        </div>
    </label>

    <input name="icon" type="file" wire:model="photo" id="icon" accept="image/*" hidden>

    <x-forms.error-message for="icon" class="text-center"/>
</div>
