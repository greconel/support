<x-layouts.ampp :title="__('Edit role')" :breadcrumbs="Breadcrumbs::render('editRole', $role)">
    <div class="card container">
        <div class="card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Admin\Roles\UpdateRoleController::class, $role) }}" method="patch">
                <div class="row">
                    <div class="col-12 mb-4">
                        <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                        <x-forms.input name="name" :value="$role->name" required />
                    </div>
                </div>

                <div class="p-3 mb-4 border rounded-3" style="max-height: 500px; overflow-y: auto">
                    @foreach($permissions as $p)
                        <x-forms.checkbox name="permissions[]" :id="\Illuminate\Support\Str::slug($p->name)" :value="$p->name" :checked="$role->hasPermissionTo($p->name)">
                            {{ $p->name }}
                        </x-forms.checkbox>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between">
                    <a href="#confirmDeleteModal" data-bs-toggle="modal" class="link-secondary">{{ __('Delete role') }}</a>

                    <x-forms.submit>{{ __('Edit role') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>

    <x-push name="modals">
        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this role?') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Admin\Roles\DestroyRoleController::class, $role)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
