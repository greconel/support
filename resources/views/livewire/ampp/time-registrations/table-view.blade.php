<header class="mb-4">
    <div class="d-flex justify-content-end align-items-center gap-2">
        <div class="btn-group">
            <a
                href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class, ['date' => request()->input('date'), 'user' => $userId]) }}"
                class="btn btn-gray-600"
            >
                {{ __('Day') }}
            </a>

            <a
                href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexWeekTimeRegistrationController::class, ['date' => request()->input('date'), 'user' => $userId]) }}"
                class="btn btn-gray-600"
            >
                {{ __('Week') }}
            </a>

            <a
                href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexMonthTimeRegistrationController::class, ['date' => request()->input('date'), 'user' => $userId]) }}"
                class="btn btn-gray-600"
            >
                {{ __('Month') }}
            </a>

            <a
                href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexTableTimeRegistrationController::class) }}"
                class="btn btn-gray-600 active"
            >
                {{ __('Table') }}
            </a>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-3 mt-4">
        @can('viewOtherUsers', \App\Models\TimeRegistration::class)
            <div
                x-data="{ userId: $wire.entangle('userId').live, select: null }"
                x-init="
                    select = new TomSelect($refs.users, { allowEmptyOption: true, hidePlaceholder: true });
                "
                style="min-width: 15rem"
                wire:ignore
            >
                <x-forms.select name="user_id" :options="$users" :values="$userId" x-model="userId" x-ref="users" />
            </div>
        @endcan

        <div
            x-data="{ projectId: $wire.entangle('projectId').live, projects: $wire.entangle('projects'), select: null }"
            x-init="
                select = new TomSelect($refs.projects, { allowEmptyOption: true, hidePlaceholder: true });

                _.forEach(projects, function(project) {
                    select.addOption({ value: project['value'], text: project['name'] })
                })

                select.addItem(projectId ?? '');

                $watch('projects', function() {
                    select.clear();
                    select.clearOptions();

                    _.forEach(projects, function(project) {
                        select.addOption({ value: project['value'], text: project['name'] })
                    })

                    select.addItem('');
                })
            "
            style="min-width: 15rem"
            wire:ignore
        >
            <select class="form-select" x-model="projectId" x-ref="projects"></select>
        </div>

        <div>
            <a
                href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\ExportTimeRegistrationsController::class, ['user' => $userId, 'project' => $projectId]) }}"
                class="btn btn-primary"
            >
                {{ __('Export time registrations') }}
            </a>
        </div>
    </div>
</header>
