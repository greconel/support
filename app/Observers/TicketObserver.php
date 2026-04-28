<?php

namespace App\Observers;

use App\Enums\AiCorrectionType;
use App\Enums\TicketStatus;
use App\Events\AiCorrectionLogCreated;
use App\Models\AiAnalysis;
use App\Models\AiCorrectionLog;
use App\Models\Ticket;
use App\Services\MotionService;
use Illuminate\Support\Facades\Log;

class TicketObserver
{
    public function updated(Ticket $ticket): void
    {
        $this->detectAiCorrection($ticket);
        $this->handleMotionIntegration($ticket);
    }

    private function handleMotionIntegration(Ticket $ticket): void
    {
        /** @var MotionService $motion */
        $motion = app(MotionService::class);

        if (! $motion->isEnabled()) {
            return;
        }

        $assigneeChanged = $ticket->wasChanged('assigned_to');
        $statusChanged = $ticket->wasChanged('status');

        // 1) Ticket gesloten → task completen + duration pushen
        if ($statusChanged && $ticket->status === TicketStatus::Closed && $ticket->motion_task_id) {
            $ticket->loadMissing('timeRegistrations');
            $totalSeconds = (int) $ticket->timeRegistrations->sum('total_time_in_seconds');

            if ($totalSeconds > 0) {
                $motion->updateDuration($ticket->motion_task_id, (int) ceil($totalSeconds / 60));
            }

            $motion->completeTask($ticket->motion_task_id);

            return;
        }

        // 2) Assignee toegewezen + geen Motion project → project aanmaken via template
        if ($assigneeChanged && $ticket->assigned_to && ! $ticket->motion_task_id) {
            $ticket->load('agent', 'client');
            $agent = $ticket->agent;

            if ($agent?->motion_user_id) {
                $clientName = $ticket->client
                    ? ($ticket->client->company ?: $ticket->client->full_name)
                    : 'Onbekend';

                $projectName = $motion->buildSupportProjectName(
                    (string) $ticket->ticket_number,
                    $clientName,
                );

                $projectDescription = $motion->buildSupportProjectDescription(
                    (string) $ticket->ticket_number,
                    $clientName,
                    $ticket->subject,
                    $ticket->impact,
                    $ticket->labels()->pluck('name')->toArray(),
                    mb_substr(strip_tags((string) $ticket->description), 0, 600),
                );

                $startDate = now()->format('Y-m-d');
                $dueDate   = \Carbon\Carbon::now()->addWeekdays(4)->format('Y-m-d');

                $projectId = $motion->createProjectFromTemplate(
                    name:                  $projectName,
                    startDate:             $startDate,
                    dueDate:               $dueDate,
                    description:           $projectDescription,
                    developerMotionUserId: $agent->motion_user_id,
                );

                if ($projectId) {
                    $ticket->updateQuietly(['motion_task_id' => $projectId]);
                    $motion->setDescriptionOnProjectTasks($projectId, $projectDescription);
                    Log::info("Motion project aangemaakt voor ticket {$ticket->ticket_number}", [
                        'project_id' => $projectId,
                    ]);
                } else {
                    Log::warning("Motion project aanmaken mislukt voor ticket {$ticket->ticket_number}");
                }
            } else {
                Log::warning("Ticket {$ticket->ticket_number}: agent heeft geen motion_user_id — project niet aangemaakt.");
            }

            return;
        }

        // 3) Assignee gewijzigd + bestaande task → assignee updaten
        if ($assigneeChanged && $ticket->assigned_to && $ticket->motion_task_id) {
            $agent = $ticket->agent;

            if ($agent?->motion_user_id) {
                $motion->updateAssignee($ticket->motion_task_id, $agent->motion_user_id);
            }
        }
    }

    private function detectAiCorrection(Ticket $ticket): void
    {
        $impactChanged = $ticket->wasChanged('impact');
        $labelsChanged = $ticket->wasChanged('ai_labelled_labels');

        if (! $impactChanged && ! $labelsChanged) {
            return;
        }

        $originals = $ticket->getOriginal();

        $wasAiImpact = (bool) ($originals['ai_labelled_impact'] ?? false);
        $wasAiLabels = (bool) ($originals['ai_labelled_labels'] ?? false);

        if (! $wasAiImpact && ! $wasAiLabels) {
            return;
        }

        $impactCorrection = $impactChanged && $wasAiImpact;
        $labelsCorrection = $labelsChanged && $wasAiLabels;

        if (! $impactCorrection && ! $labelsCorrection) {
            return;
        }

        $analysis = AiAnalysis::where('ticket_id', $ticket->id)->first();

        if (! $analysis) {
            Log::info("AI correctielog: geen AI-voorstel gevonden voor ticket {$ticket->ticket_number}.");
        }

        $type = match (true) {
            $impactCorrection && $labelsCorrection => AiCorrectionType::Both,
            $impactCorrection => AiCorrectionType::ImpactOnly,
            default => AiCorrectionType::LabelsOnly,
        };

        $agentLabels = $ticket->labels()->pluck('name')->toArray();

        $log = AiCorrectionLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'ai_impact' => $analysis?->impact?->value ?? ($originals['impact'] ?? null),
            'ai_labels' => $analysis?->labels ?? [],
            'ai_skill_version' => $analysis?->skill_version ?? 'onbekend',
            'agent_impact' => $ticket->impact?->value,
            'agent_labels' => $agentLabels,
            'ticket_subject' => $ticket->subject,
            'ticket_description_snippet' => substr(strip_tags($ticket->description), 0, 500),
            'correction_type' => $type,
        ]);

        AiCorrectionLogCreated::dispatch($log);

        Log::info("AI correctie gelogd voor ticket {$ticket->ticket_number}", [
            'type' => $type->value,
            'ai_impact' => $analysis?->impact?->value,
            'agent_impact' => $ticket->impact?->value,
        ]);
    }
}
