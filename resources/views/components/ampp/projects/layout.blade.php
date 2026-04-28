@props(['project'])

@if($project->deleted_at)
    <x-ui.alert class="alert-warning">
        {{ __('Watch it! this project is archived.') }}
    </x-ui.alert>
@endif

<div class="container-fluid row px-0">
    <div class="col-lg-3 mb-5">
        <header class="mb-4">
            <x-ampp.projects.color-name :name="$project->name" :color="$project->color" class="fs-2 text-gray-700" />
        </header>

        <nav class="nav flex-column gap-3 secondary-sidenav">
            @if(! $project->users->contains(auth()->user()))
                <x-ui.flat-sidebar-link
                    :href="action(\App\Http\Controllers\Ampp\Projects\JoinProjectController::class, $project)"
                    icon="fas fa-user-plus text-green-500"
                >
                    {{ __('Join project') }}
                </x-ui.flat-sidebar-link>
            @else
                <x-ui.flat-sidebar-link
                    :href="action(\App\Http\Controllers\Ampp\Projects\LeaveProjectController::class, $project)"
                    icon="fas fa-user-minus text-red-500"
                >
                    {{ __('Leave project') }}
                </x-ui.flat-sidebar-link>
            @endif

            <x-ui.flat-sidebar-link
                :href="action(\App\Http\Controllers\Ampp\Projects\ShowProjectOverviewController::class, $project)"
                icon="fas fa-binoculars text-gray-500"
            >
                {{ __('Overview') }}
            </x-ui.flat-sidebar-link>

            @can('viewAny', \App\Models\ProjectActivity::class)
                <x-ui.flat-sidebar-link
                    :href="action(\App\Http\Controllers\Ampp\ProjectActivities\IndexProjectActivityController::class, $project)"
                    icon="fas fa-project-diagram"
                >
                    {{ __('Activities') }}
                </x-ui.flat-sidebar-link>
            @endcan

            <x-ui.flat-sidebar-link
                :href="action(\App\Http\Controllers\Ampp\Projects\ShowProjectTodoController::class, $project)"
                icon="fas fa-list text-gray-500"
                :count="$project->todos->where('finished', '=', false)->count()"
            >
                {{ __('To do\'s') }}
            </x-ui.flat-sidebar-link>

            <x-ui.flat-sidebar-link
                href="#"
                icon="fas fa-chart-line text-gray-500"
                class="disabled"
            >
                {{ __('Gantt (coming soon)') }}
            </x-ui.flat-sidebar-link>

            <x-ui.flat-sidebar-link
                :href="action(\App\Http\Controllers\Ampp\Projects\ShowProjectCalendarController::class, $project)"
                icon="fas fa-calendar text-gray-500"
            >
                {{ __('Calendar') }}
            </x-ui.flat-sidebar-link>

            <x-ui.flat-sidebar-link
                :href="action(\App\Http\Controllers\Ampp\Projects\ShowProjectTimeRegistrationsController::class, $project)"
                icon="fas fa-table text-gray-500"
            >
                {{ __('Time registrations') }}
            </x-ui.flat-sidebar-link>

            <x-ui.flat-sidebar-link
                href="#"
                icon="fas fa-inbox text-gray-500"
                class="disabled"
            >
                {{ __('Message board (coming soon)') }}
            </x-ui.flat-sidebar-link>

            <x-ui.flat-sidebar-link
                :href="action(\App\Http\Controllers\Ampp\Projects\ShowProjectFileController::class, $project)"
                icon="fas fa-folder-open text-gray-500"
            >
                {{ __('Files') }}
            </x-ui.flat-sidebar-link>

            @can('emails', $project)
                <x-ui.flat-sidebar-link
                    :href="action(\App\Http\Controllers\Ampp\Projects\ShowProjectEmailController::class, $project)"
                    icon="fas fa-paper-plane text-gray-500"
                >
                    {{ __('E-mails') }}
                </x-ui.flat-sidebar-link>
            @endcan

            <x-ui.flat-sidebar-link
                :href="action(\App\Http\Controllers\Ampp\Projects\ShowProjectNoteController::class, $project)"
                icon="fas fa-sticky-note text-gray-500"
                :count="$project->notes->count()"
            >
                {{ __('Notes') }}
            </x-ui.flat-sidebar-link>

            @can('update', $project)
                <x-ui.flat-sidebar-link
                    :href="action(\App\Http\Controllers\Ampp\Projects\EditProjectController::class, $project)"
                    icon="fas fa-edit text-gray-500"
                >
                    {{ __('Edit project') }}
                </x-ui.flat-sidebar-link>
            @endcan

            <hr>

            <x-ui.flat-sidebar-link
                :href="action(\App\Http\Controllers\Ampp\ProjectLinks\ShowProjectLinkController::class, $project)"
                icon="fas fa-plus text-gray-500"
            >
                {{ __('Add new link') }}
            </x-ui.flat-sidebar-link>

            @foreach($project->links()->orderBy('name')->get() as $link)
                <x-ui.flat-sidebar-link
                    :href="$link->href"
                    target="_blank"
                    icon="fas fa-external-link-alt text-gray-500"
                >
                    {{ $link->name }}
                </x-ui.flat-sidebar-link>
            @endforeach
        </nav>
    </div>

    <div class="col-lg-9">
        <div class="card card-body" style="height: fit-content">
            {{ $slot }}
        </div>
    </div>
</div>
