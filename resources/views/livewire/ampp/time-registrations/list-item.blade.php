<article class="row item py-md-3">
    <div class="col-md-1 d-flex flex-row flex-md-column justify-content-between justify-content-md-start mb-2 mb-md-0">
        <span class="time fw-bolder">{{ $timeRegistration->start->format('H:i') }}</span>

        @if($timeRegistration->end)
            <span>{{ $timeRegistration->end->format('H:i') }}</span>
        @endif
    </div>

    <div class="col-md-8 mb-3 mb-md-0">
        <div class="d-flex flex-column flex-md-row">
            <div>
                <span class="project-title">
                    @if($timeRegistration->project)
                        <a
                            href="{{ action(\App\Http\Controllers\Ampp\Projects\ShowProjectOverviewController::class, $timeRegistration->project) }}"
                            class="text-decoration-none"
                        >
                            {{ $timeRegistration->project->name }}
                        </a>
                    @else
                        {{ __('No project') }}
                    @endif
                </span>

                @if($timeRegistration->projectClient)
                    <span class="client">({{ $timeRegistration->projectClient->full_name }})</span>
                @endif
            </div>

            @if($timeRegistration->project_activity_id)
                <div class="small ms-0 ms-md-2">
                    <span class="badge bg-primary">{{ $timeRegistration->projectActivity->name }}</span>
                </div>
            @endif
        </div>

        <span class="description">{!! $timeRegistration->description !!}</span>
    </div>

    <div class="col-md-3 d-flex justify-content-center justify-content-md-end align-items-center gap-3">
        @if($timeRegistration->end)
            <span class="total-time">{{ $this->calculateTotalTime($timeRegistration) }}</span>

            <button class="btn btn-success align-middle" wire:click="start">
                <i class="fas fa-clock"></i> {{ __('Start') }}
            </button>
        @else
            <span class="total-time" wire:poll.visible.20s>{{ $this->calculateTotalTime($timeRegistration) }}</span>

            <button class="btn btn-danger align-middle" wire:click="stop">
                <i class="fas fa-spinner fa-pulse"></i> {{ __('Stop') }}
            </button>
        @endif

        <a href="#" class="link-blue-700" wire:click.prevent="$dispatch('editTimeRegistration', { id: {{ $timeRegistration->id }} })">
            <i class="far fa-edit"></i>
        </a>
    </div>
</article>
