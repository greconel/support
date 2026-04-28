<?php

namespace App\Http\Controllers\Ampp\ProjectReports;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TimeRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessInvoicingController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('reports', Project::class);

        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:time_registrations,id'],
        ]);

        TimeRegistration::whereIn('id', $request->input('ids'))
            ->update(['is_billed' => true]);

        return response()->json(['success' => true]);
    }
}
