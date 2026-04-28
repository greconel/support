<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Services\GraphMailService;
use App\Services\MailToTicketService;
use Illuminate\Console\Command;

class ProcessIncomingMail extends Command
{
    protected $signature = 'mail:process';
    protected $description = 'Process unread emails from the shared helpdesk mailbox';

    public function handle(GraphMailService $graph, MailToTicketService $service): int
    {
        if (! $graph->isEnabled()) {
            $this->warn('Microsoft Graph integration is disabled or missing config — skipping.');

            return self::SUCCESS;
        }

        $this->info('Fetching mail…');
        $before = Ticket::count();
        $service->processUnreadMails();
        $after = Ticket::count();

        $this->info('Done. ' . ($after - $before) . ' new ticket(s) created.');

        return self::SUCCESS;
    }
}
