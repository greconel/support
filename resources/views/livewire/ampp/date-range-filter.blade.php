<header
    x-data="{
        open: true,
        from: $wire.entangle('fromStr'),
        till: $wire.entangle('tillStr'),
        datepicker: null
    }"
    x-init="
        datepicker = flatpickr($refs.datepicker, {
            inline: true,
            mode: 'range',
            defaultDate: [from, till],
            disableMobile: true,
            showMonths: 3,
            onChange: function(selectedDates){
                if (selectedDates.length === 2){
                    $wire.setFrom(format(selectedDates[0], 'yyyy-MM-dd'));
                    $wire.setTill(format(selectedDates[1], 'yyyy-MM-dd'));
                }
            }
        });

        $watch('from', function() {
            datepicker.setDate([from, till], false);
            datepicker.jumpToDate(from, false);
        });

        $watch('till', function() {
            datepicker.setDate([from, till], false);
            datepicker.jumpToDate(from, false);
        });
    "
    class="mb-5"
>
    <p class="fs-3 fw-bolder text-center cursor-pointer" @click="open = ! open">
        @if($from && $till)
            @if($from->isToday() && $till->isToday())
                {{ __('Today') }}
            @elseif($from->isYesterday() && $till->isYesterday())
                {{ __('Yesterday') }}
            @else
                {{ $from->format('d M Y') }} - {{ $till->format('d M Y') }}
            @endif
        @else
            {{ __('All time') }}
        @endif
    </p>

    <div class="card card-body" x-show="open">
        <div class="d-flex justify-content-between mb-3" style="overflow-x: auto">
            <nav class="nav flex-column justify-content-start me-5 me-md-0" style="white-space: nowrap">
                <a
                    @class([
                        'nav-link',
                        'fw-bolder' => $from?->isToday() && $till?->isToday()
                    ])
                    href="#"
                    wire:click.prevent="today"
                >
                    {{ __('Today') }}
                </a>

                <a
                    @class([
                        'nav-link',
                        'fw-bolder' => $from?->isYesterday() && $till?->isYesterday()
                    ])
                    href="#"
                    wire:click.prevent="yesterday"
                >
                    {{ __('Yesterday') }}
                </a>

                <a
                    @class([
                        'nav-link',
                        'fw-bolder' => $from?->isCurrentWeek() && $from?->eq($from->copy()->weekday(1)) && $till?->isCurrentWeek() && $till?->eq($till->copy()->weekday(0))
                    ])
                    href="#"
                    wire:click.prevent="thisWeek"
                >
                    {{ __('This week') }}
                </a>

                <a
                    @class([
                        'nav-link',
                        'fw-bolder' => $from?->copy()->startOfweek()->eq(now()->subWeek()->startOfWeek()) && $till?->copy()->endOfWeek()->eq(now()->subWeek()->endOfWeek())
                    ])
                    href="#"
                    wire:click.prevent="lastWeek"
                >
                    {{ __('Last week') }}
                </a>

                <a
                    @class([
                        'nav-link',
                        'fw-bolder' => $from?->eq(now()->subMonth()->firstOfMonth()) && $till?->eq(now()->subMonth()->lastOfMonth())
                    ])
                    href="#"
                    wire:click.prevent="lastMonth"
                >
                    {{ __('Last month') }}
                </a>

                <a
                    @class([
                        'nav-link',
                        'fw-bolder' => $from?->isCurrentMonth() && $from?->eq($from->copy()->firstOfMonth()) && $till?->isCurrentMonth() && $till?->eq($till->copy()->lastOfMonth())
                    ])
                    href="#"
                    wire:click.prevent="thisMonth"
                >
                    {{ __('This month') }}
                </a>

                <a
                    @class([
                       'nav-link',
                       'fw-bolder' => $from?->isCurrentYear() && $from?->eq($from->copy()->firstOfYear()) && $till?->isCurrentYear() && $till?->eq($till->copy()->lastOfYear())
                   ])
                    href="#"
                    wire:click.prevent="thisYear"
                >
                    {{ __('This year') }}
                </a>

                <a
                    @class([
                        'nav-link',
                        'fw-bolder' => $from?->eq(now()->subYear()->firstOfYear()) && $till?->eq(now()->subYear()->lastOfYear())
                    ])
                    href="#"
                    wire:click.prevent="lastYear"
                >
                    {{ __('Last year') }}
                </a>

                <a
                    @class([
                        'nav-link',
                        'fw-bolder' => $from?->eq($lastBookingYearFrom) && $till?->eq($lastBookingYearTill)
                    ])
                    href="#"
                    wire:click.prevent="lastBookingYear"
                >
                    {{ __('Last booking year') }}
                </a>

                <a
                    @class([
                        'nav-link',
                        'fw-bolder' => $from?->eq($currentBookingYearFrom) && $till?->eq($currentBookingYearTill)
                    ])
                    href="#"
                    wire:click.prevent="thisBookingYear"
                >
                    {{ __('This booking year') }}
                </a>

                <a
                    @class([
                        'nav-link',
                        'fw-bolder' => ! $from && ! $till
                    ])
                    href="#"
                    wire:click.prevent="allTime"
                >
                    {{ __('All time') }}
                </a>
            </nav>

            <div wire:ignore>
                <style>
                    .flatpickr-calendar {
                        box-shadow: none;
                        -webkit-box-shadow: none;
                    }

                    .flatpickr-months {
                        margin-bottom: 1rem;
                    }

                    .flatpickr-input {
                        display: none;
                    }
                </style>

                <input type="text" x-ref="datepicker">
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-link link-secondary me-2" @click="open = false">{{ __('Close') }}</button>
            <button class="btn btn-primary" wire:click="filter">{{ __('Filter') }}</button>
        </div>
    </div>
</header>
