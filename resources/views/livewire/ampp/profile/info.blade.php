<div>
    <x-ui.session-alert session="success" class="alert-success" />

    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
            <x-forms.input name="name" required wire:model.lazy="name" />
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.label for="email">{{ __('Email') }}</x-forms.label>
            <x-forms.input type="email" name="email" required wire:model.lazy="email" />
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <img src="{{ $photo ? $photo->temporaryUrl() : auth()->user()->profile_photo_url }}" alt="photo"
                 class="rounded-2 shadow img-fluid" style="width: 100px; height: 100px; object-fit: cover">
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="col-lg-8">
                <x-forms.file name="photo" wire:model="photo" accept="image/*" />
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-sm btn-primary" wire:click="edit">{{ __('Edit profile info') }}</button>
    </div>
</div>
