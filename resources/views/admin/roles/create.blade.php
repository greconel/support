<x-layouts.ampp :title="__('Create new role')" :breadcrumbs="Breadcrumbs::render('createRole')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new role') }}</x-ui.page-title>

        <div class="card">
            <div class="card-body">
                <x-forms.form action="{{ action(\App\Http\Controllers\Admin\Roles\StoreRoleController::class) }}"
                              method="post">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                            <x-forms.input name="name" required/>
                        </div>
                    </div>

                    <div class="p-3 mb-4 border rounded-3" style="max-height: 500px; overflow-y: auto">
                        @foreach($permissions as $p)
                            <x-forms.checkbox name="permissions[]" :id="\Illuminate\Support\Str::slug($p->name)"
                                              :value="$p->name">{{ $p->name }}</x-forms.checkbox>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end">
                        <x-forms.submit>{{ __('Create new role') }}</x-forms.submit>
                    </div>
                </x-forms.form>
            </div>
        </div>
    </div>
</x-layouts.ampp>
