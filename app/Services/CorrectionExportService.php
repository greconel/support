<?php

namespace App\Services;

use App\Models\AiCorrectionLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CorrectionExportService
{
    public function generateCsv(): string
    {
        $corrections = $this->getAllCorrections();
        $rows = $this->generateCsvRows($corrections);

        return $this->formatCsv($rows);
    }

    private function getAllCorrections(): Collection
    {
        return AiCorrectionLog::query()
            ->with([
                'ticket:id,ticket_number,subject,client_id',
                'ticket.client:id,first_name,last_name,company,email',
                'agent:id,name',
            ])
            ->orderByDesc('created_at')
            ->get();
    }

    private function generateCsvRows(Collection $corrections): array
    {
        $rows = [
            $this->generateCsvHeader(),
        ];

        foreach ($corrections as $correction) {
            $rows[] = $this->generateCsvRow($correction);
        }

        return $rows;
    }

    private function generateCsvHeader(): array
    {
        return [
            'Ticketnummer',
            'Datum',
            'Klantnaam',
            'Email',
            'Subject',
            'AI Impact',
            'AI Labels',
            'Agent Impact',
            'Agent Labels',
            'Agent',
            'Correctietype',
        ];
    }

    private function generateCsvRow(AiCorrectionLog $correction): array
    {
        $ticket = $correction->ticket;
        $client = $ticket?->client;
        $agent = $correction->agent;

        return [
            $ticket?->ticket_number ?? 'Onbekend',
            $correction->created_at?->format('d-m-Y H:i') ?? 'Onbekend',
            $client?->full_name_with_company ?? 'Onbekend',
            $client?->email ?? 'Onbekend',
            $ticket?->subject ?? 'Onbekend',
            $correction->ai_impact?->value ?? 'Niet ingesteld',
            $this->formatLabelsForCsv($correction->ai_labels),
            $correction->agent_impact?->value ?? 'Niet ingesteld',
            $this->formatLabelsForCsv($correction->agent_labels),
            $agent?->name ?? 'Onbekend',
            $this->translateCorrectionType($correction->correction_type?->value ?? ''),
        ];
    }

    private function formatLabelsForCsv(?array $labels): string
    {
        if (empty($labels)) {
            return '';
        }

        return implode('; ', $labels);
    }

    private function formatCsv(array $rows): string
    {
        $output = '';
        foreach ($rows as $row) {
            foreach ($row as $field) {
                $output .= $this->escapeCsvField((string) $field) . ',';
            }
            $output = rtrim($output, ',') . "\r\n";
        }

        return $output;
    }

    private function escapeCsvField(string $field): string
    {
        if (str_contains($field, ',') || str_contains($field, '"') || str_contains($field, "\n")) {
            return '"' . str_replace('"', '""', $field) . '"';
        }

        return $field;
    }

    private function translateCorrectionType(string $type): string
    {
        return match ($type) {
            'impact_only' => 'Alleen impact',
            'labels_only' => 'Alleen labels',
            'both' => 'Impact en labels',
            default => $type,
        };
    }

    public function generateFilename(): string
    {
        return sprintf(
            'AI-Correcties_%s.csv',
            Carbon::now()->format('Y-m-d_His'),
        );
    }

    public function generateSummary(): array
    {
        $corrections = $this->getAllCorrections();
        $typeStats = $corrections->groupBy(fn (AiCorrectionLog $c) => $c->correction_type?->value)->map->count();
        $labelStats = $this->calculateLabelStats($corrections);

        return [
            'total_corrections' => $corrections->count(),
            'impact_only' => $typeStats->get('impact_only', 0),
            'labels_only' => $typeStats->get('labels_only', 0),
            'both' => $typeStats->get('both', 0),
            'top_labels' => array_slice($labelStats, 0, 10, true),
        ];
    }

    private function calculateLabelStats(Collection $corrections): array
    {
        $labelStats = [];

        foreach ($corrections as $correction) {
            $aiLabels = $correction->ai_labels ?? [];
            foreach ($aiLabels as $label) {
                $labelStats[$label] = ($labelStats[$label] ?? 0) + 1;
            }
        }

        arsort($labelStats);

        return $labelStats;
    }
}
