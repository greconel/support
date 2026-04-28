<?php

namespace App\Http\Controllers\Ampp\Helpdesk\AiSkill;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateAiSkillJob;
use App\Models\AiCorrectionLog;

class TriggerUpdateAiSkillController extends Controller
{
    public function __invoke()
    {
        $this->authorize('manage ai skill');

        $pending = AiCorrectionLog::query()
            ->where('processed', false)
            ->where('ignore_in_training', false)
            ->count();

        if ($pending === 0) {
            session()->flash('info', __('No unprocessed corrections — skill is already up-to-date.'));

            return back();
        }

        UpdateAiSkillJob::dispatch();

        session()->flash('success', __('Skill update started for :count correction(s). Runs in the background.', [
            'count' => $pending,
        ]));

        return back();
    }
}
