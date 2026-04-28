<table class="table table-borderless table-striped" style="white-space: nowrap">
    <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Company') }}</th>
            <th>{{ __('Hours') }}</th>
            <th>{{ __('Billable hours') }}</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @php /* @var \App\Models\Client $client */  @endphp
        @foreach($clients->sortBy('first_name') as $client)
            <tr>
                <td>
                    <a href="{{ action(\App\Http\Controllers\Ampp\Clients\ShowClientController::class, $client) }}" target="_blank">
                        {{ $client->full_name }}
                    </a>
                </td>

                <td>
                    {{ $client->company }}
                </td>

                <td>
                    {{ $calculateHours($client) }}
                </td>

                <td>
                    {{ $calculateBillableHours($client) }}
                </td>

                <td class="text-end">
                    <a
                        href="{{ action(\App\Http\Controllers\Ampp\ProjectReports\ShowDetailedTimeReportController::class, [
                            'from' => $from->format('Y-m-d'),
                            'till' => $till->format('Y-m-d'),
                            'type' => 'client',
                            'id' => $client->id
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
