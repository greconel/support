<div style="max-height: 1000px; overflow-y: scroll;">
    <section class="timeline position-relative container-fluid" wire:loading.class="timeline-loading" wire:target="load" wire:init="$set('readyToLoad', true)">
        @forelse($activitiesByDate as $date => $activities)
            <x-ampp.activity-logs.timeline.header>
                @if(\Carbon\Carbon::createFromFormat('d/m/Y', $date)->isToday())
                    {{ __('activityLogs.today') }}
                @elseif(\Carbon\Carbon::createFromFormat('d/m/Y', $date)->isYesterday())
                    {{ __('activityLogs.yesterday') }}
                @else
                    {{ $date }}
                @endif
            </x-app.activity-logs.timeline.header>

            @foreach($activities as $activity)
                <div class="row">
                    <div
                        @class(['col-md-6 px-0', 'offset-md-6' => $loop->even])
                        @if($loop->last && $hasMorePages)
                            x-data
                            x-intersect.once="$wire.load()"
                        @endif
                    >
                        <x-ampp.activity-logs.timeline.event :side="$loop->odd ? 'left' : 'right'" :activity="$activity" />
                    </div>
                </div>
            @endforeach
        @empty
            @if($readyToLoad)
                <p class="text-center my-0 py-0">{{ __('general.nothing_to_see') }}</p>
            @else
                <p class="text-center my-0 py-0">{{ __('general.loading') }}</p>
            @endif
        @endforelse
    </section>

    @if($hasMorePages)
        <div class="d-flex justify-content-center mt-5">
            <button class="btn btn-primary" type="button" wire:click="load" wire:loading.attr="disabled" wire:target="load">
                <div wire:loading wire:target="load">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </div>

                <div wire:loading.remove wire:target="load">
                    {{ __('general.load_more') }}
                </div>
            </button>
        </div>
    @endif
</div>
