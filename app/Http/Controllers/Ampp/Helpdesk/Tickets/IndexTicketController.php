<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Tickets;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class IndexTicketController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', Ticket::class);

        $tickets = Ticket::query()
            ->with(['client', 'agent', 'labels'])
            ->when($request->string('status')->toString(), function ($q, $status) {
                if ($status === 'open') {
                    $q->where('status', '!=', TicketStatus::Closed);
                }
                else {
                    $q->where('status', $status);
                }
            })
            ->when($request->string('q')->toString(), function ($q, $term) {
                $q->where(function ($qq) use ($term){
                    $qq->where('subject', 'like', "%{$term}%")
                        ->orWhere('ticket_number', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('agent'), function ($q) use ($request) {
                $q->where('assigned_to', $request->integer('agent'));
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $statuses = TicketStatus::cases();

        if ($request->ajax()) {
            return view('ampp.helpdesk.tickets.partials.results', compact('tickets'));
        }

        return view('ampp.helpdesk.tickets.index', compact('tickets', 'statuses'));
    }
}
