<?php

namespace App\Http\Controllers\Ampp\Helpdesk\Corrections;

use App\Http\Controllers\Controller;
use App\Models\AiCorrectionLog;
use Illuminate\Http\Request;

class ToggleIgnoreCorrectionController extends Controller
{
    public function __invoke(Request $request, AiCorrectionLog $log)
    {
        $this->authorize('view ai corrections');

        $validated = $request->validate([
            'ignore_in_training' => 'required|boolean',
            'ignore_reason'      => 'nullable|string|max:500',
        ]);

        $log->update([
            'ignore_in_training' => $validated['ignore_in_training'],
            'ignore_reason'      => $validated['ignore_in_training']
                ? ($validated['ignore_reason'] ?? null)
                : null,
        ]);

        $label = $validated['ignore_in_training']
            ? 'marked as exception'
            : 'exception removed';

        return back()->with('success', "Correction {$label}.");
    }
}
