<div class="d-flex flex-column justify-content-center align-items-center">
    <label for="avatar" class="position-relative overflow-hidden rounded-circle border" style="cursor: pointer">
        <img
            src="{{ $photo ? $photo->temporaryUrl() : auth()->user()->profile_photo_url }}"
            alt="avatar"
            class="img-fluid"
            style="object-fit: cover; width: 150px; height: 150px"
        >

        <div
            class="position-absolute start-50 translate-middle-x small w-100 d-flex justify-content-center align-items-center text-white"
            style="top: 70%; height: 30%; white-space: nowrap; background-color:rgba(0, 0, 0, 0.2);"
        >
            {{ __('Change') }}
        </div>
    </label>

    <input name="avatar" type="file" wire:model="photo" id="avatar" accept="image/*" hidden>

    <x-forms.error-message for="avatar" class="text-center" />
</div>
