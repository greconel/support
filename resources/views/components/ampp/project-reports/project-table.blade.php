<table class="table table-borderless table-striped" style="white-space: nowrap">
    <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Client') }}</th>
            <th>{{ __('Category') }}</th>
            <th>{{ __('Hours') }}</th>
            <th>{{ __('Billable hours') }}</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @php /* @var \App\Models\Project $project */  @endphp
        @foreach($projects->sortBy(['client.first_name', 'name']) as $project)
            <tr>
                <td>
                    <a href="{{ action(\App\Http\Controllers\Ampp\Projects\ShowProjectOverviewController::class, $project) }}" target="_blank">
                        <x-ampp.projects.color-name :name="$project->name" :color="$project->color" class="fw-bolder" />
                    </a>
                </td>

                <td class="text-wrap" style="width: 30rem">
                    @if($project->client_id)
                        <a href="{{ action(\App\Http\Controllers\Ampp\Clients\ShowClientController::class, $project->client) }}" target="_blank">
                            {{ $project->client->full_name }}
                        </a>
                    @endif
                </td>

                <td>
                    {{ $project->category?->label() }}
                </td>

                <td>
                    {{ $calculateHours($project) }}
                </td>

                <td>
                    {{ $calculateBillableHours($project) }}
                </td>

                <td class="text-end">
                    <a
                        href="{{ action(\App\Http\Controllers\Ampp\ProjectReports\ShowDetailedTimeReportController::class, [
                            'from' => $from->format('Y-m-d'),
                            'till' => $till->format('Y-m-d'),
                            'type' => 'project',
                            'id' => $project->id
                        ]) }}"
                        target="_blank"
                    >
                        {{ __('Hours in detail') }}
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
