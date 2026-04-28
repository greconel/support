<x-layouts.ampp :title="__('Edit permission')" :breadcrumbs="Breadcrumbs::render('editPermission', $permission)">
    <div class="container">
        <x-ui.page-title>{{ __('Edit permission') }}</x-ui.page-title>

        <div class="card">
            <div class="card-body">
                <x-forms.form
                    action="{{ action(\App\Http\Controllers\Admin\Permissions\UpdatePermissionController::class, $permission) }}"
                    method="patch">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                            <x-forms.input name="name" :value="$permission->name" required/>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="#confirmDeleteModal" data-bs-toggle="modal" class="link-secondary">{{ __('Delete permission') }}</a>

                        <x-forms.submit>{{ __('Edit permission') }}</x-forms.submit>
                    </div>
                </x-forms.form>
            </div>
        </div>
    </div>

    <x-push name="modals">
        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this permission?') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Admin\Permissions\DestroyPermissionController::class, $permission)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
