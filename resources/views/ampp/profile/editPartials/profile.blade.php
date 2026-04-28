<div class="card card-body mb-5">
    <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Profile\UpdateProfileController::class) }}" method="patch" :hasFiles="true">
        <div class="row">
            <div class="col-lg-3">
                <livewire:ampp.profile.update-avatar />
            </div>

            <div class="col-lg-9">
                <div class="mb-3">
                    <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                    <x-forms.input name="name" :value="$user->name" />
                </div>

                <div class="mb-3">
                    <x-forms.label for="email">{{ __('Email') }}</x-forms.label>
                    <x-forms.input name="email" type="email" :value="$user->email" />
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <x-forms.submit>{{ __('Update profile') }}</x-forms.submit>
        </div>
    </x-forms.form>
</div>
