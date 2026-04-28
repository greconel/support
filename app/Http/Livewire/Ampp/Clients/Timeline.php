<?php

namespace App\Http\Livewire\Ampp\Clients;

use App\Models\Client;
use App\Models\ClientContact;
use App\Models\Note;
use App\Models\Todo;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class Timeline extends Component
{
    public bool $readyToLoad = false;
    public Client $client;
    public int $perPage = 15;

    public function mount(Client $client = null)
    {
        $this->client = $client;
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
                Client::class,
                ClientContact::class,
                Todo::class,
                Note::class,
            ])
            ->orderByDesc('created_at')
            ->get()
            ->filter(function ($value, $key){
                if ($value->subject == $this->client->withoutRelations()){
                    return true;
                }

                if ($value->subject_type == ClientContact::class && $value->subject?->client == $this->client->withoutRelations()){
                    return true;
                }

                if ($value->subject_type == Todo::class && $value->subject?->morphable == $this->client->withoutRelations()){
                    return true;
                }

                if ($value->subject_type == Note::class && $value->subject?->morphable == $this->client->withoutRelations()){
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
