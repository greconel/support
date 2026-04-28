<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Tickets;

use App\Enums\TicketImpact;
use App\Enums\TicketStatus;
use App\Events\TicketCreated;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreTicketController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('create', Ticket::class);

        $validated = $request->validate([
            'client_id'         => 'required|exists:clients,id',
            'subject'           => 'required|string|max:255',
            'description'       => 'required|string',
            'impact'            => ['nullable', 'in:' . collect(TicketImpact::cases())->pluck('value')->implode(',')],
            'assigned_to'       => 'nullable|exists:users,id',
            'labels'            => 'array',
            'labels.*'          => 'exists:labels,id',
            'send_confirmation' => 'nullable|boolean',
        ], [
            'client_id.required'   => 'Please select a client.',
            'subject.required'     => 'Subject is required.',
            'description.required' => 'Description is required.',
        ]);

        DB::beginTransaction();
        try {
            $status = $validated['assigned_to'] ? TicketStatus::InProgress : TicketStatus::New;

            $ticket = Ticket::create([
                'ticket_number' => Ticket::generateTicketNumber(),
                'subject'       => $validated['subject'],
                'description'   => $validated['description'],
                'status'        => $status,
                'impact'        => $validated['impact'] ?? null,
                'client_id'     => $validated['client_id'],
                'assigned_to'   => $validated['assigned_to'] ?? null,
            ]);

            if (!empty($validated['labels'])) {
                $ticket->labels()->sync($validated['labels']);
            }

            DB::commit();

            event(new TicketCreated($ticket, $request->boolean('send_confirmation')));

            return redirect()
                ->route('tickets.show', $ticket)
                ->with('success', "Ticket {$ticket->ticket_number} created successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
