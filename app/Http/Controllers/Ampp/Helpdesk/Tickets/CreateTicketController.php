<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Tickets;

use App\Enums\TicketImpact;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Label;
use App\Models\Ticket;
use App\Models\User;

class CreateTicketController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', Ticket::class);

        $clients = Client::query()->orderBy('company')->orderBy('last_name')->get();
        $agents = User::query()->role('helpdesk agent')->orderBy('name')->get();
        $labels = Label::query()->orderBy('name')->get();
        $impacts = TicketImpact::cases();

        return view('ampp.helpdesk.tickets.create', compact('clients', 'agents', 'labels', 'impacts'));
    }
}
