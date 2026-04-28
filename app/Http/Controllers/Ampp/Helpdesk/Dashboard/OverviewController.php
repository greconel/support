<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Dashboard;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\AiCorrectionLog;
use App\Models\Ticket;

class OverviewController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Ticket::class);

        $totalTickets  = Ticket::count();
        $openTickets   = Ticket::where('status', '!=', TicketStatus::Closed)->count();
        $closedTickets = Ticket::where('status', TicketStatus::Closed)->count();

        $totalCorrections = AiCorrectionLog::count();
        $unprocessed      = AiCorrectionLog::where('processed', false)->count();
        $impactOnly       = AiCorrectionLog::where('correction_type', 'impact_only')->count();
        $labelsOnly       = AiCorrectionLog::where('correction_type', 'labels_only')->count();
        $both             = AiCorrectionLog::where('correction_type', 'both')->count();

        $currentSkillVersion = 'onbekend';
        $skillPath = storage_path(config('helpdesk.ai.skill_path', 'ai-skill/labeling-skill.md'));
        if (file_exists($skillPath)) {
            $skillContent = file_get_contents($skillPath);
            if (preg_match('/\*\*Versie:\*\*\s*(.+)/m', $skillContent, $matches)) {
                $currentSkillVersion = trim($matches[1]);
            }
        }

        $recentCorrections = AiCorrectionLog::with(['ticket:id,ticket_number,subject', 'agent:id,name'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('ampp.helpdesk.overview', compact(
            'totalTickets',
            'openTickets',
            'closedTickets',
            'totalCorrections',
            'unprocessed',
            'impactOnly',
            'labelsOnly',
            'both',
            'currentSkillVersion',
            'recentCorrections',
        ));
    }
}
