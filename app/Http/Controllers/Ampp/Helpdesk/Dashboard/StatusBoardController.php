<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Dashboard;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class StatusBoardController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', Ticket::class);

        $statuses = [];
        foreach (TicketStatus::cases() as $status) {
            $statuses[$status->value] = $status->label();
        }

        $ticketsByStatus = [];
        foreach (array_keys($statuses) as $status) {
            $ticketsByStatus[$status] = Ticket::with(['client', 'agent', 'labels'])
                ->where('status', $status)
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        return view('ampp.helpdesk.status-board', compact('ticketsByStatus', 'statuses'));
    }
}
