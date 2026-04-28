<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Dashboard;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;

class AgentsBoardController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Ticket::class);

        $openStatuses = [
            TicketStatus::New->value,
            TicketStatus::InProgress->value,
            TicketStatus::OnHold->value,
            TicketStatus::ToClose->value,
        ];

        // Haal alle helpdesk agents op met hun ticket count
        $agents = User::role('helpdesk agent')
            ->withCount(['assignedTickets' => function ($query) use ($openStatuses) {
                $query->whereIn('status', $openStatuses);
            }])
            ->orderBy('name')
            ->get();

        // Haal niet-toegewezen tickets op (alleen open tickets)
        $unassignedTickets = Ticket::with(['client', 'labels'])
            ->whereNull('assigned_to')
            ->whereIn('status', $openStatuses)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('ampp.helpdesk.agents-board', compact('agents', 'unassignedTickets', 'openStatuses'));
    }
}
