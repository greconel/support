<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Tickets;

use App\Http\Controllers\Controller;
use App\Models\AiCorrectionLog;
use App\Models\Label;
use App\Models\Ticket;
use App\Models\User;
use App\Services\MotionService;

class ShowTicketController extends Controller
{
    public function __invoke(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['client', 'agent', 'labels', 'timeRegistrations.user', 'messages.media', 'messages.user']);

        $allLabels     = Label::orderBy('name')->get();
        $correctionLog = AiCorrectionLog::where('ticket_id', $ticket->id)
            ->latest()
            ->first();

        $agents = User::role('helpdesk agent')->orderBy('name')->get();

        // Motion projectnaam ophalen
        $motionProjectName = null;
        if ($ticket->client?->motion_project_id) {
            try {
                $motion   = app(MotionService::class);
                $projects = $motion->getProjects();
                foreach ($projects as $project) {
                    if ($project['id'] === $ticket->client->motion_project_id) {
                        $motionProjectName = $project['name'];
                        break;
                    }
                }
            } catch (\Throwable $e) {
                // Motion niet beschikbaar
            }
        }

        return view('ampp.helpdesk.tickets.show', compact('ticket', 'allLabels', 'correctionLog', 'motionProjectName', 'agents'));
    }
}
