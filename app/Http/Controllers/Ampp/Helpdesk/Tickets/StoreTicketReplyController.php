<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Services\GraphMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class StoreTicketReplyController extends Controller
{
    public function __invoke(Request $request, Ticket $ticket)
    {
        $this->authorize('reply', $ticket);

        $request->validate([
            'body' => 'required|string|min:1',
            'attachments' => 'nullable|array|max:10',
            'attachments.*' => 'file|max:10240|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,txt,csv,zip',
        ]);

        $user    = auth()->user();
        $graph   = app(GraphMailService::class);
        $bodyText = strip_tags($request->body);
        $bodyHtml = nl2br(e($request->body));

        // Sla het bericht op
        $message = TicketMessage::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => $user->id,
            'from_email'  => config('helpdesk.graph.mailbox'),
            'from_name'   => $user->name,
            'direction'   => 'outbound',
            'subject'     => "Re: [{$ticket->ticket_number}] {$ticket->subject}",
            'body_html'   => $bodyHtml,
            'body_text'   => $bodyText,
            'sent_at'     => now(),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $uploadedFile) {
                if (! $uploadedFile || ! $uploadedFile->isValid()) {
                    continue;
                }

                $message
                    ->addMedia($uploadedFile->getRealPath())
                    ->usingFileName($uploadedFile->getClientOriginalName())
                    ->usingName(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME))
                    ->withCustomProperties([
                        'uploaded_by_user_id' => $user->id,
                    ])
                    ->toMediaCollection('attachments');
            }
        }

        $graphAttachments = [];
        foreach ($message->getMedia('attachments') as $attachment) {
            $path = $attachment->getPath();

            if (! $path || ! File::exists($path)) {
                Log::warning('Reply attachment ontbreekt op disk', [
                    'ticket_id' => $ticket->id,
                    'ticket_message_id' => $message->id,
                    'media_id' => $attachment->id,
                ]);

                continue;
            }

            $bytes = File::get($path);
            if ($bytes === false) {
                continue;
            }

            $graphAttachments[] = [
                '@odata.type' => '#microsoft.graph.fileAttachment',
                'name' => $attachment->file_name,
                'contentType' => $attachment->mime_type ?: 'application/octet-stream',
                'contentBytes' => base64_encode($bytes),
            ];
        }

        // Stuur de e-mail via Graph
        $html = view('ampp.helpdesk.emails.ticket-reply', [
            'ticket'  => $ticket,
            'message' => $message,
            'body'    => $bodyHtml,
            'agentName' => $user->name,
        ])->render();

        $payload = [
            'message' => [
                'subject' => "Re: [{$ticket->ticket_number}] {$ticket->subject}",
                'body'    => [
                    'contentType' => 'HTML',
                    'content'     => $html,
                ],
                'toRecipients' => [[
                    'emailAddress' => [
                        'address' => $ticket->client->email,
                        'name'    => $ticket->client->full_name,
                    ],
                ]],
                'attachments' => $graphAttachments,
            ],
            'saveToSentItems' => true,
        ];

        try {
            $graph->sendMail($payload);
        } catch (\Throwable $e) {
            Log::error('Reply sending failed', ['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Reply sent.');
    }
}
