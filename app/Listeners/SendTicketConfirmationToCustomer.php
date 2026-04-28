<?php

namespace App\Listeners;

use App\Enums\TicketMessageDirection;
use App\Events\TicketCreated;
use App\Models\TicketMessage;
use App\Services\GraphMailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendTicketConfirmationToCustomer implements ShouldQueue
{
    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(private GraphMailService $graph) {}

    public function handle(TicketCreated $event): void
    {
        if (! $event->sendConfirmation) {
            return;
        }

        $ticket = $event->ticket;
        $cacheKey = "ticket-confirmation-sent:{$ticket->id}";
        $lockKey = "ticket-confirmation-lock:{$ticket->id}";

        if (Cache::has($cacheKey)) {
            Log::info("Bevestigingsmail overgeslagen voor ticket {$ticket->ticket_number}: al verstuurd.");
            return;
        }

        $lock = Cache::lock($lockKey, 120);

        if (! $lock->get()) {
            Log::info("Bevestigingsmail overgeslagen voor ticket {$ticket->ticket_number}: al in behandeling.");
            return;
        }

        $client = $ticket->client;

        try {
            if (! $client?->email) {
                Log::info("Bevestigingsmail overgeslagen voor ticket {$ticket->ticket_number}: geen client-email.");
                return;
            }

            if (! $this->graph->isEnabled()) {
                Log::warning("Bevestigingsmail overgeslagen voor ticket {$ticket->ticket_number}: GraphMailService disabled.");
                return;
            }

            $html = view('ampp.helpdesk.emails.ticket-confirmation', ['ticket' => $ticket])->render();

            $payload = [
                'message' => [
                    'subject' => "[{$ticket->ticket_number}] Bevestiging: {$ticket->subject}",
                    'body' => [
                        'contentType' => 'HTML',
                        'content' => $html,
                    ],
                    'toRecipients' => [
                        [
                            'emailAddress' => [
                                'address' => $client->email,
                                'name' => $client->full_name,
                            ],
                        ],
                    ],
                    'internetMessageHeaders' => [
                        [
                            'name' => 'X-Ticket-ID',
                            'value' => (string) $ticket->id,
                        ],
                        [
                            'name' => 'X-Ticket-Number',
                            'value' => $ticket->ticket_number,
                        ],
                    ],
                ],
                'saveToSentItems' => true,
            ];

            $sent = $this->graph->sendMail($payload);

            if ($sent) {
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => null,
                    'from_email' => config('helpdesk.graph.mailbox'),
                    'from_name' => 'Helpdesk',
                    'direction' => TicketMessageDirection::Outbound,
                    'subject' => "[{$ticket->ticket_number}] Bevestiging: {$ticket->subject}",
                    'body_html' => $html,
                    'body_text' => strip_tags($html),
                    'message_id' => null,
                    'in_reply_to' => null,
                    'internet_message_id' => null,
                    'sent_at' => now(),
                ]);

                Cache::put($cacheKey, true, now()->addDay());

                Log::info("Bevestigingsmail verstuurd voor ticket {$ticket->ticket_number} naar {$client->email}");
            }
        } catch (\Throwable $e) {
            Log::error("Bevestigingsmail mislukt voor ticket {$ticket->ticket_number}", [
                'error' => $e->getMessage(),
            ]);
        } finally {
            $lock->release();
        }
    }
}