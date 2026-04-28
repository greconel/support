<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestGraphConnection extends Command
{
    protected $signature = 'graph:test';
    protected $description = 'Test the Microsoft Graph API connection';

    public function handle(): int
    {
        $tenantId = config('helpdesk.graph.tenant_id');
        $clientId = config('helpdesk.graph.client_id');
        $clientSecret = config('helpdesk.graph.client_secret');
        $mailbox = config('helpdesk.graph.mailbox');

        if (! $tenantId || ! $clientId || ! $clientSecret || ! $mailbox) {
            $this->error('Graph config incomplete — check MICROSOFT_TENANT_ID / CLIENT_ID / CLIENT_SECRET / MAILBOX.');

            return self::FAILURE;
        }

        $this->info('Step 1: fetching access token…');

        $tokenResponse = Http::asForm()->post(
            "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token",
            [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => 'https://graph.microsoft.com/.default',
            ],
        );

        if (! $tokenResponse->successful()) {
            $this->error('✗ Token fetch failed');
            $this->line($tokenResponse->body());

            return self::FAILURE;
        }

        $this->info('✓ Access token received');
        $token = $tokenResponse->json('access_token');

        $this->info("Step 2: reading inbox of {$mailbox}…");

        $inboxResponse = Http::withToken($token)
            ->get("https://graph.microsoft.com/v1.0/users/{$mailbox}/mailFolders/inbox/messages", [
                '$top' => 10,
                '$select' => 'subject,from,receivedDateTime,isRead',
                '$orderby' => 'receivedDateTime desc',
            ]);

        if (! $inboxResponse->successful()) {
            $this->error('✗ Inbox read failed');
            $this->line($inboxResponse->body());

            return self::FAILURE;
        }

        $messages = $inboxResponse->json('value', []);
        $this->info('✓ ' . count($messages) . ' messages found');
        $this->newLine();

        $headers = ['Status', 'Date', 'From', 'Subject'];
        $rows = [];

        foreach ($messages as $msg) {
            $rows[] = [
                $msg['isRead'] ? 'Read' : 'UNREAD',
                \Carbon\Carbon::parse($msg['receivedDateTime'])->setTimezone(config('app.timezone', 'UTC'))->format('d-m-Y H:i'),
                data_get($msg, 'from.emailAddress.address', '?'),
                mb_substr($msg['subject'] ?? '(no subject)', 0, 50),
            ];
        }

        $this->table($headers, $rows);

        return self::SUCCESS;
    }
}
