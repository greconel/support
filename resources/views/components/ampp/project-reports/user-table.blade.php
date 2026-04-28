<table class="table table-borderless" style="white-space: nowrap">
    <thead>
        <tr>
            <th></th>
            <th>{{ __('User') }}</th>
            <th>{{ __('Hours') }}</th>
            <th>{{ __('Billable hours') }}</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @php /* @var \App\Models\User $user */  @endphp
        @foreach($users->sortBy('name') as $user)
            <tr class="border-bottom">
                <td class="align-text-top" style="width: 5rem">
                    <img src="{{ $user->profile_photo_url }}" alt="avatar" class="avatar">
                </td>

                <td class="align-text-top" style="min-width: 200px">
                    <a href="{{ action(\App\Http\Controllers\Admin\Users\ShowUserController::class, $user) }}" target="_blank">
                        {{ $user->name }}
                    </a>
                </td>

                <td class="align-text-top" style="min-width: 200px">
                    {{ $calculateHours($user) }}
                </td>

                <td class="align-text-top" style="min-width: 200px">
                    {{ $calculateBillableHours($user) }}
                </td>

                <td>
                    <div
                        x-data="{
                            data: {
                                labels: {{ json_encode($getProjectsWithHours($user)['projects']) }},
                                datasets: [{
                                    data: {{ json_encode($getProjectsWithHours($user)['durations']) }},
                                    backgroundColor: {{ json_encode($getProjectsWithHours($user)['colors']) }}
                                }],
                                chart: null
                            }
                        }"
                        x-init="
                            chart = new Chart($refs.chart, {
                                type: 'pie',
                                data: data,
                                options: {
                                    responsive: true,
                                    aspectRatio: 4,
                                    parsing: { key: 'seconds' },
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            callbacks: {
                                                label: (ctx) => `${ctx.label}: ${ctx.raw.hours}`
                                            }
                                        }
                                    }
                                }
                            });
                        "
                        wire:key="{{ time() . '_' . $loop->index }}"
                        style="width: 100%; max-height: 150px"
                    >
                        <canvas x-ref="chart"></canvas>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
