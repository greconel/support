<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('editProject', $project)">
    <x-ampp.projects.layout :project="$project">
        @can('create', \App\Models\ProjectActivity::class)
            <div class="d-flex justify-content-end mb-5">
                <a href="{{ action(\App\Http\Controllers\Ampp\ProjectActivities\CreateProjectActivityController::class, $project) }}"
                   class="btn btn-primary">
                    {{ __('Add activity') }}
                </a>
            </div>
        @endcan

        <table class="table table-striped table-borderless">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Budget (hours)') }}</th>
                    <th>{{ __('Actual hours') }}</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse($activities->sortBy('name') as $activity)
                    <tr>
                        <td>
                            {{ $activity->name }}

                            @if(! $activity->is_active)
                                <span class="text-red-400">({{ __('Not active') }})</span>
                            @endif

                            <span class="d-block text-muted">{{ $activity->description }}</span>
                        </td>
                        <td>{{ $activity->budget_in_hours }}</td>
                        <td>{{ $activity->actual_hours }}</td>
                        <td style="width: 25%">
                            @if($activity->budget_in_hours > 0)
                                @php
                                    try {
                                        $percentage = ($activity->actual_hours / $activity->budget_in_hours) * 100;
                                    } catch(DivisionByZeroError){
                                        $percentage = 0;
                                    }

                                    $color = match(true){
                                        $percentage <= 75 => 'success',
                                        $percentage <= 90 => 'warning',
                                        default => 'danger'
                                    };
                                @endphp

                                <div class="progress">
                                    <div
                                        class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $color }}"
                                        role="progressbar"
                                        style="width: {{ $percentage }}%;"
                                        aria-valuenow="{{ $percentage }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                    >
                                        {{ (int) $percentage }}%
                                    </div>
                                </div>
                            @endif
                        </td>

                        <td>
                            @can('update', $activity)
                                <a
                                    href="{{ action(\App\Http\Controllers\Ampp\ProjectActivities\EditProjectActivityController::class, [$project, $activity]) }}"
                                    class="link-warning"
                                >
                                    {{ __('Edit') }}
                                </a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">{{ __('No activities available for this project..') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-ampp.projects.layout>
</x-layouts.ampp>
