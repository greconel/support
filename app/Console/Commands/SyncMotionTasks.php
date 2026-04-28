<?php

namespace App\Console\Commands;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use App\Services\MotionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncMotionTasks extends Command
{
    protected $signature = 'motion:sync {--dry-run : Show changes without persisting}';
    protected $description = 'Sync Motion task statuses back to tickets';

    public function handle(MotionService $motion): int
    {
        if (! $motion->isEnabled()) {
            $this->warn('Motion integration is disabled or missing config — skipping.');

            return self::SUCCESS;
        }

        $tickets = Ticket::query()
            ->whereNotNull('motion_task_id')
            ->where('status', '!=', TicketStatus::Closed)
            ->with(['agent'])
            ->get();

        if ($tickets->isEmpty()) {
            $this->info('No tickets with Motion tasks found.');

            return self::SUCCESS;
        }

        $this->info("📋 Checking {$tickets->count()} ticket(s)…");

        $synced = 0;
        $closed = 0;
        $reassigned = 0;
        $notFound = 0;

        foreach ($tickets as $ticket) {
            $task = $motion->getTask($ticket->motion_task_id);

            if ($task === null) {
                $notFound++;
                $this->line("  ⚠ {$ticket->ticket_number}: task not found in Motion (ID: {$ticket->motion_task_id})");
                continue;
            }

            $changed = false;

            // 1) Task completed in Motion → close ticket
            if (($task['completed'] ?? false) === true && $ticket->status !== TicketStatus::Closed) {
                if ($this->option('dry-run')) {
                    $this->line("  [dry-run] {$ticket->ticket_number}: would be closed");
                }
                else {
                    $ticket->update([
                        'status' => TicketStatus::Closed,
                        'closed_at' => now(),
                    ]);
                    $this->info("  ✓ {$ticket->ticket_number}: closed (task completed in Motion)");
                    $closed++;
                }

                $changed = true;
            }

            // 2) Assignee changed in Motion → update agent
            $motionAssigneeId = data_get($task, 'assignees.0.id')
                             ?? data_get($task, 'assignee.id');

            if ($motionAssigneeId && ! $changed) {
                $currentAgentMotionId = $ticket->agent?->motion_user_id;

                if ($motionAssigneeId !== $currentAgentMotionId) {
                    $newAgent = User::query()->where('motion_user_id', $motionAssigneeId)->first();

                    if ($newAgent) {
                        if ($this->option('dry-run')) {
                            $this->line("  [dry-run] {$ticket->ticket_number}: agent would be {$newAgent->name}");
                        }
                        else {
                            $ticket->update(['assigned_to' => $newAgent->id]);
                            $this->info("  ✓ {$ticket->ticket_number}: agent updated to {$newAgent->name}");
                            $reassigned++;
                        }
                    }
                }
            }

            $synced++;
        }

        $this->newLine();
        $this->info("Done. Checked: {$synced} | Closed: {$closed} | Reassigned: {$reassigned} | Not found: {$notFound}");

        Log::info('Motion sync finished', compact('synced', 'closed', 'reassigned', 'notFound'));

        return self::SUCCESS;
    }
}
