<?php

namespace App\Services;

use App\Enums\ClientType;
use App\Enums\TicketMessageDirection;
use App\Enums\TicketSource;
use App\Enums\TicketStatus;
use App\Events\TicketCreated;
use App\Events\TicketReplyReceived;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MailToTicketService
{
    public function __construct(
        private GraphMailService $graph,
        private AttachmentProcessor $attachmentProcessor,
    ) {}

    public function processUnreadMails(): void
    {
        $messages = $this->graph->getUnreadMessages(25);

        foreach ($messages as $msg) {
            try {
                $this->processMessage($msg);
                $this->graph->markAsRead($msg['id']);
            }
            catch (\Throwable $e) {
                Log::error('Mail verwerken mislukt', [
                    'message_id' => $msg['id'],
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    public function processMessage(array $msg): void
    {
        $graphId = $msg['id'];
        $internetMsgId = $msg['internetMessageId'] ?? null;
        $subject = $msg['subject'] ?? '(geen onderwerp)';
        $fromEmail = data_get($msg, 'from.emailAddress.address');
        $fromName = data_get($msg, 'from.emailAddress.name', $fromEmail);
        $hasAttachments = $msg['hasAttachments'] ?? false;

        $bodyHtml = $this->sanitizeHtml(data_get($msg, 'body.content'));

        if ($hasAttachments || str_contains($bodyHtml ?? '', 'cid:')) {
            $bodyHtml = $this->attachmentProcessor->processInlineImages($bodyHtml ?? '', $graphId);
        }

        $bodyText = strip_tags($bodyHtml ?? '');

        if (TicketMessage::where('message_id', $graphId)->exists()) {
            return;
        }

        $receivedAt = $this->parseReceivedAt($msg['receivedDateTime'] ?? null, $graphId);

        $headers = $this->graph->getMessageHeaders($graphId);
        $inReplyTo = $headers['in-reply-to'] ?? null;
        $ticket = $this->findExistingTicket($inReplyTo, $subject);
        $isNewTicket = $ticket === null;

        if ($isNewTicket) {
            $client = $this->findOrCreateClient($fromEmail, $fromName);

            $ticket = Ticket::create([
                'ticket_number' => Ticket::generateTicketNumber(),
                'subject' => $subject,
                'description' => $bodyHtml,
                'status' => TicketStatus::New,
                'client_id' => $client->id,
                'source' => TicketSource::Email,
                'last_inbound_message_id' => $internetMsgId,
            ]);
        }
        else {
            $ticket->update([
                'last_inbound_message_id' => $internetMsgId,
                'status' => $ticket->status === TicketStatus::Closed ? TicketStatus::New : $ticket->status,
            ]);
        }

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'direction' => TicketMessageDirection::Inbound,
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
            'message_id' => $graphId,
            'in_reply_to' => $inReplyTo,
            'internet_message_id' => $internetMsgId,
            'sent_at' => $receivedAt->toDateTimeString(),
        ]);

        if ($hasAttachments) {
            $message->loadMissing('ticket');
            $this->attachmentProcessor->processAttachments($graphId, $message);
        }

        if ($isNewTicket) {
            TicketCreated::dispatch($ticket, true);
        }
        else {
            TicketReplyReceived::dispatch($ticket, $bodyText);
        }
    }

    private function sanitizeHtml(?string $html): ?string
    {
        if (! $html) {
            return $html;
        }

        $html = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $html);
        $html = preg_replace('/<head[^>]*>.*?<\/head>/is', '', $html);
        $html = preg_replace('/<\/?(html|body)[^>]*>/i', '', $html);

        return trim($html);
    }

    private function parseReceivedAt(?string $raw, string $graphId): Carbon
    {
        $timezone = config('app.timezone', 'UTC');

        try {
            return $raw
                ? Carbon::parse($raw)->setTimezone($timezone)
                : now($timezone);
        }
        catch (\Throwable $e) {
            Log::warning('receivedDateTime kon niet geparsed worden, fallback naar now()', [
                'message_id' => $graphId,
                'receivedDateTime' => $raw,
                'error' => $e->getMessage(),
            ]);

            return now($timezone);
        }
    }

    private function findExistingTicket(?string $inReplyTo, string $subject): ?Ticket
    {
        if ($inReplyTo) {
            $msg = TicketMessage::query()
                ->where('internet_message_id', $inReplyTo)
                ->orWhere('internet_message_id', trim($inReplyTo, '<>'))
                ->first();

            if ($msg) {
                return $msg->ticket;
            }

            $ticket = Ticket::where('last_inbound_message_id', $inReplyTo)->first();

            if ($ticket) {
                return $ticket;
            }
        }

        if (preg_match('/\[#(\d+)\]/', $subject, $matches)) {
            $ticketNumber = '#' . str_pad($matches[1], 4, '0', STR_PAD_LEFT);

            return Ticket::where('ticket_number', $ticketNumber)->first();
        }

        return null;
    }

    private function findOrCreateClient(string $fromEmail, ?string $fromName): Client
    {
        $client = Client::query()->where('email', $fromEmail)->first();

        if ($client) {
            return $client;
        }

        $name = trim($fromName ?? $fromEmail);
        $parts = preg_split('/\s+/', $name, 2);
        $firstName = $parts[0] ?? 'Unknown';
        $lastName = $parts[1] ?? '';

        return Client::create([
            'first_name' => $firstName,
            'last_name' => $lastName ?: $firstName,
            'email' => $fromEmail,
            'type' => ClientType::Client,
        ]);
    }
}
