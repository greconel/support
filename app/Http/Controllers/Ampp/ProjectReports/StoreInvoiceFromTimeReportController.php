<?php

namespace App\Http\Controllers\Ampp\ProjectReports;

use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ampp\Invoices\EditInvoiceLinesController;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\TimeRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StoreInvoiceFromTimeReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('reports', Project::class);
        $this->authorize('create', Invoice::class);

        $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:time_registrations,id'],
            'notes' => ['nullable', 'string'],
            'po_number' => ['nullable', 'string', 'max:255'],
            'sections' => ['required', 'array'],
            'sections.*.title' => ['required', 'string', 'max:255'],
            'sections.*.description' => ['nullable', 'string'],
            'sections.*.total_seconds' => ['required', 'integer'],
            'sections.*.billable_seconds' => ['required', 'integer'],
        ]);

        // Create draft invoice
        $invoice = new Invoice([
            'type' => InvoiceType::Debit,
            'client_id' => $request->input('client_id'),
            'expiration_date' => now()->addDays(30),
            'custom_created_at' => now(),
            'notes' => $request->input('notes'),
            'po_number' => $request->input('po_number'),
        ]);
        $invoice->save();

        // Create billing lines from sections
        $order = 1;
        foreach ($request->input('sections') as $section) {
            $description = $section['description'] ?? '';

            // Append time info to description
            $timeInfo = __('Total time') . ': ' . $this->formatDuration($section['total_seconds']) . ' | '
                . __('Billable time') . ': ' . $this->formatDuration($section['billable_seconds']);

            $description = $description
                ? $description . "\n\n" . $timeInfo
                : $timeInfo;

            $invoice->billingLines()->create([
                'type' => 'text',
                'order' => $order++,
                'text' => $section['title'],
                'description' => $description,
                'price' => 0,
                'amount' => 1,
                'subtotal' => 0,
                'vat' => 21,
                'discount' => 0,
            ]);
        }

        // Mark time registrations as billed
        TimeRegistration::whereIn('id', $request->input('ids'))
            ->update(['is_billed' => true]);

        return redirect()->action(EditInvoiceLinesController::class, $invoice);
    }

    private function formatDuration(int $seconds): string
    {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);

        if ($h > 0 && $m > 0) return "{$h}h {$m}m";
        if ($h > 0) return "{$h}h";
        return "{$m}m";
    }
}
