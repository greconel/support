<?php

namespace App\Console\Commands;

use App\Services\GraphMailService;
use Illuminate\Console\Command;

class MarkLatestMailsUnread extends Command
{
    protected $signature = 'mail:unread {count=1}';
    protected $description = 'Reset the latest N mails back to unread (useful for testing the inbox flow)';

    public function handle(GraphMailService $graph): int
    {
        if (! $graph->isEnabled()) {
            $this->warn('Microsoft Graph integration is disabled or missing config — skipping.');

            return self::SUCCESS;
        }

        $count = (int) $this->argument('count');
        $messages = $graph->getLatestMessages($count);

        if (empty($messages)) {
            $this->info('No messages found.');

            return self::SUCCESS;
        }

        foreach ($messages as $msg) {
            $graph->markAsUnread($msg['id']);
            $this->info("Marked unread: {$msg['subject']}");
        }

        return self::SUCCESS;
    }
}
