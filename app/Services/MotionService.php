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
        $this->apiKey      = config('helpdesk.motion.api_key');
        $this->workspaceId = config('helpdesk.motion.workspace_id');
        $this->baseUrl     = config('helpdesk.motion.base_url', 'https://api.usemotion.com/v1');
    }

    public function isEnabled(): bool
    {
        return (bool) config('helpdesk.motion.enabled', true)
            && $this->apiKey
            && $this->workspaceId;
    }

    public function buildSupportProjectName(string $ticketNumber, string $clientName): string
    {
        $ticketNumber = ltrim($ticketNumber, '#');
        $normalizedTicketNumber = ctype_digit($ticketNumber)
            ? str_pad($ticketNumber, 4, '0', STR_PAD_LEFT)
            : $ticketNumber;

        return mb_substr("SUPPORT - [#{$normalizedTicketNumber}] {$clientName}", 0, 120);
    }

    public function buildSupportProjectDescription(
        string $ticketNumber,
        string $clientName,
        string $subject,
        TicketImpact|string|null $impact = null,
        array $labels = [],
        ?string $description = null
    ): string {
        $ticketNumber = ltrim($ticketNumber, '#');
        $labelTekst   = ! empty($labels) ? implode(', ', $labels) : 'Geen labels';

        $impactTekst = match (true) {
            $impact instanceof TicketImpact => ucfirst($impact->value),
            is_string($impact)             => ucfirst($impact),
            default                        => 'Niet opgegeven',
        };

        $beschrijving = trim((string) $description);
        $beschrijving = $beschrijving !== '' ? $beschrijving : 'Geen beschrijving beschikbaar.';

        return implode("\n", [
            "Ticket: #{$ticketNumber}",
            "Klant: {$clientName}",
            "Onderwerp: {$subject}",
            "Impact: {$impactTekst}",
            "Labels: {$labelTekst}",
            '',
            'Beschrijving:',
            $beschrijving,
        ]);
    }

    public function createProjectFromTemplate(
        string $name,
        string $startDate,
        string $dueDate,
        ?string $description = null,
        ?string $developerMotionUserId = null
    ): ?string {
        if (! $this->isEnabled()) {
            return null;
        }

        try {
            $definitionId               = config('helpdesk.motion.support_template_id');
            $stage1Id                   = config('helpdesk.motion.support_stage_1_id');
            $stage2Id                   = config('helpdesk.motion.support_stage_2_id');
            $projectManagerMotionUserId = config('helpdesk.motion.support_project_manager_id');

            if (! $stage1Id || ! $stage2Id) {
                Log::warning('Motion stages niet ingesteld voor template-project', [
                    'support_stage_1_id_present' => (bool) $stage1Id,
                    'support_stage_2_id_present' => (bool) $stage2Id,
                ]);

                return null;
            }

            if (! $developerMotionUserId) {
                Log::warning('Motion template project kan niet worden aangemaakt zonder developer Motion user id');

                return null;
            }

            if (! $projectManagerMotionUserId) {
                Log::warning('Motion template project kan niet worden aangemaakt zonder project manager Motion user id');

                return null;
            }

            $stage1Due = \Carbon\Carbon::parse($startDate)->addWeekdays(3)->format('Y-m-d');
            $stage2Due = \Carbon\Carbon::parse($stage1Due)->addWeekdays(1)->format('Y-m-d');

            $payload = [
                'name'                => $name,
                'workspaceId'         => $this->workspaceId,
                'projectDefinitionId' => $definitionId,
                'startDate'           => $startDate,
                'dueDate'             => $dueDate,
                'description'         => $description,
                'stages'              => [
                    [
                        'stageDefinitionId' => $stage1Id,
                        'dueDate'           => $stage1Due,
                        'variableInstances'  => [
                            ['variableName' => 'Developer',       'value' => $developerMotionUserId],
                            ['variableName' => 'Project manager', 'value' => $projectManagerMotionUserId],
                        ],
                    ],
                    [
                        'stageDefinitionId' => $stage2Id,
                        'dueDate'           => $stage2Due,
                        'variableInstances'  => [
                            ['variableName' => 'Developer',       'value' => $developerMotionUserId],
                            ['variableName' => 'Project manager', 'value' => $projectManagerMotionUserId],
                        ],
                    ],
                ],
            ];

            $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->post("{$this->baseUrl}/projects", $payload);

            if ($response->successful()) {
                Log::info('Motion project aangemaakt', ['body' => $response->body()]);

                return $response->json('project.id') ?? $response->json('id');
            }

            Log::error('Motion createProjectFromTemplate mislukt', [
                'response' => $response->body(),
                'status'   => $response->status(),
            ]);

            return null;
        }
        catch (\Throwable $e) {
            Log::error('Motion createProjectFromTemplate exception', ['message' => $e->getMessage()]);

            return null;
        }
    }

    public function setDescriptionOnProjectTasks(string $projectId, string $description): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        try {
            $response = Http::withHeaders(['X-API-Key' => $this->apiKey])
                ->get("{$this->baseUrl}/tasks", [
                    'projectId'   => $projectId,
                    'workspaceId' => $this->workspaceId,
                ]);

            if (! $response->successful()) {
                Log::error('Motion setDescriptionOnProjectTasks: taken ophalen mislukt', [
                    'project_id' => $projectId,
                    'response'   => $response->body(),
                ]);

                return;
            }

            $tasks = $response->json('tasks', []);

            foreach ($tasks as $task) {
                $taskId = $task['id'] ?? null;

                if (! $taskId) {
                    continue;
                }

                $patch = Http::withHeaders(['X-API-Key' => $this->apiKey])
                    ->patch("{$this->baseUrl}/tasks/{$taskId}", [
                        'description' => $description,
                    ]);

                if (! $patch->successful()) {
                    Log::warning('Motion setDescriptionOnProjectTasks: taak patchen mislukt', [
                        'task_id'  => $taskId,
                        'response' => $patch->body(),
                    ]);
                }
            }
        }
        catch (\Throwable $e) {
            Log::error('Motion setDescriptionOnProjectTasks exception', ['message' => $e->getMessage()]);
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
                    'status'  => $response->status(),
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
