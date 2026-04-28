<x-layouts.ampp :title="$user->name" :breadcrumbs="Breadcrumbs::render('showUser', $user)">
    <div class="container">
        @if($user->deleted_at)
            <x-ui.alert class="alert-warning">
                {{ __('This is an archived user') }}
            </x-ui.alert>
        @endif

        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        {{ $user->name }}
                    </div>

                    <div class="card-body">
                        <img
                            src="{{ $user->profile_photo_url }}"
                            alt="Profile photo"
                            class="avatar img-fluid mx-auto d-block rounded-lg mb-4"
                            style="object-fit: cover; width: 100px; height: 100px"
                        />

                        <div class="col-md-9 mx-auto">
                            <div class="data-row">
                                <span>{{ __('Email') }}</span>
                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                            </div>

                            <div class="data-row">
                                <span>{{ __('Created at') }}</span>
                                {{ $user->created_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="data-row">
                                <span>{{ __('Last updated at') }}</span>
                                {{ $user->updated_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="data-row">
                                <span>{{ __('Roles') }}</span>
                                {{ implode(", ", $user->getRoleNames()->toArray()) }}
                            </div>

                            <div class="data-row">
                                <span>{{ __('Motion user ID') }}</span>
                                {{ $user->motion_user_id }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        {{ __('Actions') }}
                    </div>

                    <div class="card-body d-grid gap-4">
                        <a href="{{ action(\App\Http\Controllers\Admin\Users\EditUserController::class, $user) }}" class="btn btn-primary">
                            {{ __('Edit user') }}
                        </a>

                        @if(! $user->deleted_at)
                            <button type="button" data-bs-toggle="modal" data-bs-target="#confirmArchiveModal" class="btn btn-gray-500">
                                {{ __('Archive user') }}
                            </button>
                        @else
                            <button type="button" data-bs-toggle="modal" data-bs-target="#confirmRestoreModal" class="btn btn-gray-500">
                                {{ __('Restore user') }}
                            </button>
                        @endif

                        <button type="button" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" class="btn btn-danger mt-4">
                            {{ __('Delete user') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                {{ __('Activities') }}
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless rounded-3">
                        <tbody>
                            @forelse($activityLogs as $activityLog)
                                <tr class="border-bottom">
                                    <td class="align-text-top text-center py-4">{{ $activityLog->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="align-text-top text-center py-4">{{ $activityLog->description }}</td>
                                    <td class="align-text-top text-center py-4">{{ $activityLog->subject_type }}</td>
                                    <td class="align-text-top text-center py-4">{{ $activityLog->subject_id }}</td>
                                    <td class="py-4">
                                        <code><pre>{{ print_r($activityLog->properties->toArray(), true) }}</pre></code>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">{{ __('Nothing to see here..') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    {{ $activityLogs->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-push name="modals">
        @if(! $user->deleted_at)
            <x-ui.confirmation-modal id="confirmArchiveModal">
                <x-slot name="content">
                    {{ __('Are you sure you want to archive this user? The user will not be able to login and will no longer appear in user selection dropdowns.') }}
                </x-slot>

                <x-slot name="actions">
                    <x-forms.form :action="action(\App\Http\Controllers\Admin\Users\ArchiveUserController::class, $user)" method="patch">
                        <button class="btn btn-primary">{{ __('Archive') }}</button>
                    </x-forms.form>
                </x-slot>
            </x-ui.confirmation-modal>
        @else
            <x-ui.confirmation-modal id="confirmRestoreModal">
                <x-slot name="content">
                    {{ __('Are you sure you want to restore this user? The user will again be able to login and will appear in user selection dropdowns.') }}
                </x-slot>

                <x-slot name="actions">
                    <x-forms.form :action="action(\App\Http\Controllers\Admin\Users\RestoreUserController::class, $user)" method="patch">
                        <button class="btn btn-primary">{{ __('Restore') }}</button>
                    </x-forms.form>
                </x-slot>
            </x-ui.confirmation-modal>
        @endif

        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this user? This action can not be reverted!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Admin\Users\DestroyUserController::class, $user)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
