<?php

namespace App\Services;

use App\Enums\TicketImpact;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MotionService
{
    private ?string $apiKey;
    private ?string $workspaceId;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('helpdesk.motion.api_key');
        $this->workspaceId = config('helpdesk.motion.workspace_id');
        $this->baseUrl = config('helpdesk.motion.base_url', 'https://api.usemotion.com/v1');
    }

    public function isEnabled(): bool
    {
        return (bool) config('helpdesk.motion.enabled', true)
            && $this->apiKey
            && $this->workspaceId;
    }

    public function createTask(
        string $title,
        string $description,
        string $motionUserId,
        ?string $projectId = null,
        TicketImpact|string|null $impact = null,
        array $labels = [],
    ): ?string {
        if (! $this->isEnabled()) {
            return null;
        }

        try {
            $priority = match (true) {
                $impact instanceof TicketImpact => $impact->motionPriority(),
                is_string($impact) => match ($impact) {
                    'high' => 'ASAP',
                    'medium' => 'HIGH',
                    'low' => 'MEDIUM',
                    default => 'MEDIUM',
                },
                default => 'MEDIUM',
            };

            $labelText = ! empty($labels) ? implode(', ', $labels) : '—';
            $impactText = $impact instanceof TicketImpact
                ? ucfirst($impact->value)
                : ($impact ? ucfirst((string) $impact) : '—');

            $snippet = substr(strip_tags($description), 0, 300);

            $body = "**[SUPPORT]** · Impact: {$impactText} · Labels: {$labelText}\n\n{$snippet}";

            $payload = [
                'name' => $title,
                'description' => $body,
                'workspaceId' => $this->workspaceId,
                'assigneeId' => $motionUserId,
                'priority' => $priority,
                'status' => 'Todo',
            ];

            if ($projectId) {
                $payload['projectId'] = $projectId;
            }

            $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->post("{$this->baseUrl}/tasks", $payload);

            if ($response->successful()) {
                return $response->json('task.id') ?? $response->json('id');
            }

            Log::error('Motion createTask mislukt', ['response' => $response->body()]);

            return null;
        }
        catch (\Throwable $e) {
            Log::error('Motion createTask exception', ['message' => $e->getMessage()]);

            return null;
        }
    }

    public function updateAssignee(string $motionTaskId, string $motionUserId): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        try {
            $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->patch("{$this->baseUrl}/tasks/{$motionTaskId}", [
                    'assigneeId' => $motionUserId,
                ]);

            if (! $response->successful()) {
                Log::error('Motion updateAssignee mislukt', ['response' => $response->body()]);
            }
        }
        catch (\Throwable $e) {
            Log::error('Motion updateAssignee exception', ['message' => $e->getMessage()]);
        }
    }

    public function completeTask(string $motionTaskId): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        try {
            $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->patch("{$this->baseUrl}/tasks/{$motionTaskId}", [
                    'status' => 'Completed',
                ]);

            if (! $response->successful()) {
                Log::error('Motion completeTask mislukt', ['response' => $response->body()]);
            }
        }
        catch (\Throwable $e) {
            Log::error('Motion completeTask exception', ['message' => $e->getMessage()]);
        }
    }

    public function updateDuration(string $motionTaskId, int $minutes): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        try {
            $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->patch("{$this->baseUrl}/tasks/{$motionTaskId}", [
                    'duration' => $minutes,
                ]);

            if (! $response->successful()) {
                Log::error('Motion updateDuration mislukt', ['response' => $response->body()]);
            }
        }
        catch (\Throwable $e) {
            Log::error('Motion updateDuration exception', ['message' => $e->getMessage()]);
        }
    }

    public function getProjects(): array
    {
        if (! $this->isEnabled()) {
            return [];
        }

        $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
            ->get("{$this->baseUrl}/projects", [
                'workspaceId' => $this->workspaceId,
            ]);

        if (! $response->successful()) {
            Log::error('Motion getProjects mislukt', ['body' => $response->body()]);

            return [];
        }

        return $response->json('projects', []);
    }

    public function getTask(string $motionTaskId): ?array
    {
        if (! $this->isEnabled()) {
            return null;
        }

        try {
            $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->get("{$this->baseUrl}/tasks/{$motionTaskId}");

            if ($response->status() === 404) {
                return null;
            }

            if (! $response->successful()) {
                Log::error('Motion getTask mislukt', [
                    'task_id' => $motionTaskId,
                    'status' => $response->status(),
                ]);

                return null;
            }

            return $response->json('task') ?? $response->json();
        }
        catch (\Throwable $e) {
            Log::error('Motion getTask exception', ['message' => $e->getMessage()]);

            return null;
        }
    }
}
