<?php

namespace App\Http\Livewire\Ampp;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;

class DateRangeFilter extends Component
{
    /** @var Carbon|null $from */
    public $from;

    /** @var Carbon|null $from */
    public $till;

    public string $fromStr = '';

    public string $tillStr = '';

    public Carbon $currentBookingYearFrom;

    public Carbon $currentBookingYearTill;

    public Carbon $lastBookingYearFrom;

    public Carbon $lastBookingYearTill;

    public function mount(bool $defaultAllTime = false)
    {
        // current booking year
        $this->currentBookingYearFrom = now()->setDateTime(now()->year, 7, 1, 0, 0);

        if ($this->currentBookingYearFrom->isFuture()){
            $this->currentBookingYearFrom->subYear();
        }

        $this->currentBookingYearTill = now()->setDateTime(now()->year, 6, 30, 23, 59, 59);

        if ($this->currentBookingYearTill->isPast()){
            $this->currentBookingYearTill->addYear();
        }

        // last booking year
        $this->lastBookingYearFrom = $this->currentBookingYearFrom->copy()->subYear();

        $this->lastBookingYearTill = $this->currentBookingYearTill->copy()->subYear();

        try {
            if ($defaultAllTime){
                $this->from = null;
                $this->till = null;
                return;
            }

            $this->from = $this->fromStr ? Carbon::parse($this->fromStr) : now()->firstOfMonth();
            $this->till = $this->tillStr ? Carbon::parse($this->tillStr) : now()->lastOfMonth();
        } catch (\Exception){
            $this->from = now()->firstOfMonth();
            $this->till = now()->lastOfMonth();
        }
    }

    public function queryString(): array
    {
        return [
            'fromStr' => ['as' => 'from', 'except' => ''],
            'tillStr' => ['as' => 'till', 'except' => ''],
        ];
    }

    public function dehydrate()
    {
        $this->fromStr = $this->from ? $this->from->format('Y-m-d') : '';
        $this->tillStr = $this->till ? $this->till->format('Y-m-d') : '';
    }

    public function filter()
    {
        $this->dispatch(
            'shareDates',
            from: $this->from?->format('Y-m-d'),
            till: $this->till?->format('Y-m-d')
        );
    }

    public function setFrom(string $from)
    {
        $this->from = Carbon::parse($from);
    }

    public function setTill(string $till)
    {
        $this->till = Carbon::parse($till);
    }

    public function today()
    {
        $this->from = now();
        $this->till = now();
    }

    public function yesterday()
    {
        $this->from = now()->subDay();
        $this->till = now()->subDay();
    }

    public function thisWeek()
    {
        $this->from = now()->startofWeek();
        $this->till = now()->endOfWeek();
    }

    public function lastWeek()
    {
        $this->from = now()->subWeek()->startofWeek();
        $this->till = now()->subWeek()->endOfWeek();
    }

    public function thisMonth()
    {
        $this->from = now()->firstOfMonth();
        $this->till = now()->lastOfMonth();
    }

    public function lastMonth()
    {
        $this->from = now()->subMonth()->firstOfMonth();
        $this->till = now()->subMonth()->lastOfMonth();
    }

    public function thisYear()
    {
        $this->from = now()->firstOfYear();
        $this->till = now()->lastOfYear();
    }

    public function lastYear()
    {
        $this->from = now()->subYear()->firstOfYear();
        $this->till = now()->subYear()->lastOfYear();
    }

    public function allTime()
    {
        $this->from = null;
        $this->till = null;
    }

    public function thisBookingYear()
    {
        $this->from = $this->currentBookingYearFrom;
        $this->till = $this->currentBookingYearTill;
    }

    public function lastBookingYear()
    {
        $this->from = $this->lastBookingYearFrom;
        $this->till = $this->lastBookingYearTill;
    }

    public function render(): View
    {
        return view('livewire.ampp.date-range-filter');
    }
}
