<?php

namespace App\Jobs;

use App\Models\Label;
use App\Models\Ticket;
use App\Services\AILabelingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AnalyseTicketJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public Ticket $ticket) {}

    public function handle(AILabelingService $ai): void
    {
        if ($this->ticket->impact && $this->ticket->labels()->exists()) {
            Log::info("Ticket {$this->ticket->ticket_number}: AI analyse overgeslagen, al manueel ingesteld.");
            return;
        }

        $this->ticket->loadMissing('client');

        $result = $ai->analyse(
            $this->ticket->subject,
            $this->ticket->description,
            $this->ticket->id,
            $this->ticket->client?->full_name_with_company,
            $this->ticket->client?->motion_project_id,
        );

        if (! $result) {
            Log::warning("Ticket {$this->ticket->ticket_number}: AI analyse gaf geen resultaat.");
            return;
        }

        if (! $this->ticket->impact && ! empty($result['impact'])) {
            $this->ticket->updateQuietly([
                'impact' => $result['impact'],
                'ai_labelled_impact' => true,
            ]);
        }

        if (! $this->ticket->labels()->exists() && ! empty($result['labels'])) {
            $labels = Label::whereIn('name', $result['labels'])->get();

            $syncData = $labels->mapWithKeys(fn (Label $label) => [
                $label->id => ['ai_labelled' => true],
            ])->toArray();

            $this->ticket->labels()->sync($syncData);
            $this->ticket->updateQuietly(['ai_labelled_labels' => true]);
        }

        Log::info("Ticket {$this->ticket->ticket_number}: AI analyse succesvol.", $result);
    }

    public function failed(\Throwable $e): void
    {
        Log::error("AnalyseTicketJob mislukt voor ticket {$this->ticket->ticket_number}", [
            'error' => $e->getMessage(),
        ]);
    }
}
