<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Tickets;

use App\Enums\TicketImpact;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class UpdateTicketController extends Controller
{
    public function __invoke(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'impact'      => ['nullable', 'in:' . collect(TicketImpact::cases())->pluck('value')->implode(',')],
            'labels'      => 'array',
            'labels.*'    => 'exists:labels,id',
            'status'      => ['nullable', 'in:' . collect(TicketStatus::cases())->pluck('value')->implode(',')],
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $impactChanged = ($validated['impact'] ?? null) !== $ticket->impact?->value;

        $ticket->update([
            'impact'             => $validated['impact'] ?? null,
            'status'             => $validated['status'] ?? $ticket->status,
            'assigned_to'        => array_key_exists('assigned_to', $validated)
                                        ? $validated['assigned_to']
                                        : $ticket->assigned_to,
            'ai_labelled_impact' => $impactChanged ? false : $ticket->ai_labelled_impact,
            'closed_at'          => ($validated['status'] ?? null) === TicketStatus::Closed->value && $ticket->status !== TicketStatus::Closed
                ? now()
                : (($validated['status'] ?? null) !== TicketStatus::Closed->value ? null : $ticket->closed_at),
        ]);

        if ($request->has('labels')) {
            $huidigeLabels = $ticket->labels->pluck('id')->sort()->values()->toArray();
            $nieuweLabels  = collect($validated['labels'] ?? [])->map(fn($id) => (int)$id)->sort()->values()->toArray();
            $labelsChanged = $huidigeLabels !== $nieuweLabels;

            $ticket->labels()->sync($validated['labels'] ?? []);

            if ($labelsChanged) {
                $ticket->update(['ai_labelled_labels' => false]);
            }
        } else {
            $ticket->labels()->sync([]);
            $ticket->update(['ai_labelled_labels' => false]);
        }

        return back()->with('success', 'Ticket updated successfully.');
    }
}
