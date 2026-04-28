<?php

namespace App\Http\Livewire\Ampp\Billing;

use App\Models\Email;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\BillingLines;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class Timeline extends Component
{
    public bool $readyToLoad = false;
    public Model $model;
    public int $perPage = 15;

    public function mount(Model $model = null)
    {
        $this->model = $model;
    }

    #[On('refreshTimeline')]
    public function refreshTimeline()
    {
        // This triggers a component refresh
    }

    public function load()
    {
        $this->perPage += 5;
    }

    public function render(): View
    {
        if (!$this->readyToLoad){
            return view('livewire.ampp.timeline', [
                'activitiesByDate' => [],
                'hasMorePages' => false
            ]);
        }

        $activities = Activity::whereNotNull(['causer_type', 'subject_type'])
            ->whereIn('subject_type', [
                Quotation::class,
                Invoice::class,
                BillingLines::class,
                Email::class
            ])
            ->orderByDesc('created_at')
            ->get()
            ->filter(function ($value, $key) {
                // current model
                if ($value->subject == $this->model->withoutRelations()) {
                    return true;
                }

                // quotation page
                if ($this->model instanceof Quotation) {
                    if ($value->subject_type == Invoice::class && $value->subject?->quotation_id == $this->model->id) {
                        return true;
                    }
                }

                // invoice page (with a quotation relation)
                if ($this->model instanceof Invoice && $this->model->quotation) {
                    if ($value->subject_type == Quotation::class && $value->subject?->id == $this->model->quotation_id) {
                        return true;
                    }

                    if ($value->subject_type == Invoice::class && $value->subject?->quotation_id == $this->model->quotation_id) {
                        return true;
                    }
                }

                // mails
                if ($value->subject_type == Email::class && $value->subject?->morphable == $this->model->withoutRelations()) {
                    return true;
                }

                return false;
            });

        $paginator = new LengthAwarePaginator($activities, $activities->count(), $this->perPage);

        $activitiesByDate = $activities->take($this->perPage)
            ->groupBy([
                function ($q){
                    return Carbon::parse($q->created_at)->format('d/m/Y');
                },
            ]);

        return view('livewire.ampp.timeline', [
            'activitiesByDate' => $activitiesByDate,
            'hasMorePages' => $paginator->hasMorePages()
        ]);
    }
}
