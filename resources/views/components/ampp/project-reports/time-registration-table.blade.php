<table class="table table-borderless table-striped" style="white-space: nowrap">
    <thead>
        <tr>
            <th>{{ __('User') }}</th>
            <th>{{ __('Start') }}</th>
            <th>{{ __('End') }}</th>
            <th>{{ __('Hours') }}</th>
            <th>{{ __('Change project') }}</th>
            <th>{{ __('Description') }}</th>
        </tr>
    </thead>

    <tbody>
        @php /* @var \App\Models\TimeRegistration $timeRegistration */  @endphp
        @foreach($timeRegistrations->sortBy('user_id') as $timeRegistration)
            <tr>
                <td>
                    <a href="{{ action(\App\Http\Controllers\Admin\Users\ShowUserController::class, $timeRegistration->user) }}" target="_blank">
                        {{ $timeRegistration->user->name }}
                    </a>
                </td>

                <td>
                    {{ $timeRegistration->start->format('l d M Y H:i') }}
                </td>

                <td>
                    {{ $timeRegistration->end?->format('l d M Y H:i') }}
                </td>

                <td>
                    {{ $timeRegistration->end ? $calculateHours($timeRegistration) : '' }}
                </td>

                <td style="width: 25%">
                    <livewire:ampp.project-reports.project-select :time-registration="$timeRegistration" :wire:key="$loop->index . '_' . time()" />
                </td>

                <td class="text-gray-500 description-column">
                    <div style="overflow-y: auto; max-height: 75px">
                        {!! $timeRegistration->description !!}
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
