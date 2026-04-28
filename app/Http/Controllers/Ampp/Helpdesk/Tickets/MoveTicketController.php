<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Tickets;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class MoveTicketController extends Controller
{
    public function __invoke(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'status'      => ['nullable', 'in:' . collect(TicketStatus::cases())->pluck('value')->implode(',')],
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $newAssignedTo = array_key_exists('assigned_to', $validated)
            ? $validated['assigned_to']
            : $ticket->assigned_to;

        if ($newAssignedTo && ! $ticket->assigned_to && $ticket->status === TicketStatus::New) {
            $newStatus = TicketStatus::InProgress;
        } else {
            $newStatus = isset($validated['status'])
                ? TicketStatus::from($validated['status'])
                : $ticket->status;
        }

        $ticket->update([
            'status'      => $newStatus,
            'assigned_to' => $newAssignedTo,
            'closed_at'   => ($newStatus === TicketStatus::Closed && $ticket->status !== TicketStatus::Closed)
                ? now()
                : ($newStatus !== TicketStatus::Closed ? null : $ticket->closed_at),
        ]);

        return response()->json(['success' => true]);
    }
}
