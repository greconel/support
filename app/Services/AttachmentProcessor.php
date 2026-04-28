<?php

namespace App\Services;

use App\Models\TicketMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttachmentProcessor
{
    public function __construct(private GraphMailService $graph) {}

    public function processInlineImages(string $bodyHtml, string $graphMessageId): string
    {
        if (! str_contains($bodyHtml, 'cid:')) {
            return $bodyHtml;
        }

        $attachments = $this->graph->getAttachments($graphMessageId);

        foreach ($attachments as $attachment) {
            if (empty($attachment['isInline'])
                || empty($attachment['contentId'])
                || empty($attachment['contentBytes'])
                || ! str_starts_with($attachment['contentType'] ?? '', 'image/')
            ) {
                continue;
            }

            $contentId = $attachment['contentId'];
            $contentBytes = base64_decode($attachment['contentBytes']);
            $filename = uniqid('inline_', true) . '_'
                . preg_replace('/[^a-zA-Z0-9_\.-]/', '', $attachment['name'] ?? 'image.png');
            $path = 'helpdesk-inline/' . $filename;

            Storage::disk('public')->put($path, $contentBytes);

            $bodyHtml = str_replace("cid:{$contentId}", '/storage/' . $path, $bodyHtml);
        }

        return $bodyHtml;
    }

    public function processAttachments(string $graphMessageId, TicketMessage $message): void
    {
        $attachments = $this->graph->getAttachments($graphMessageId);

        foreach ($attachments as $attachment) {
            if (! empty($attachment['isInline'])) {
                continue;
            }

            if (($attachment['@odata.type'] ?? '') === '#microsoft.graph.itemAttachment') {
                continue;
            }

            try {
                $bytes = ! empty($attachment['contentBytes'])
                    ? base64_decode($attachment['contentBytes'])
                    : $this->graph->getAttachmentContent($graphMessageId, $attachment['id']);

                if (empty($bytes)) {
                    Log::warning('Bijlage heeft geen inhoud', [
                        'filename' => $attachment['name'] ?? 'onbekend',
                        'ticket' => $message->ticket?->ticket_number,
                    ]);
                    continue;
                }

                $originalFilename = $attachment['name'] ?? 'bijlage';

                $message
                    ->addMediaFromString($bytes)
                    ->usingFileName($originalFilename)
                    ->usingName($originalFilename)
                    ->withCustomProperties([
                        'mime_type' => $attachment['contentType'] ?? null,
                    ])
                    ->toMediaCollection('attachments');

                Log::info("Bijlage opgeslagen: {$originalFilename} voor ticket {$message->ticket?->ticket_number}");
            }
            catch (\Throwable $e) {
                Log::error('Bijlage verwerken mislukt', [
                    'filename' => $attachment['name'] ?? 'onbekend',
                    'ticket' => $message->ticket?->ticket_number,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
