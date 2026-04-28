<?php

namespace App\Http\Controllers\Ampp\Helpdesk\AiSkill;

use App\Http\Controllers\Controller;
use App\Models\AiCorrectionLog;
use Illuminate\Support\Carbon;

class IndexAiSkillController extends Controller
{
    public function __invoke()
    {
        $this->authorize('manage ai skill');

        $skillPath = storage_path(config('helpdesk.ai.skill_path', 'ai-skill/labeling-skill.md'));

        $skillContent = file_exists($skillPath)
            ? file_get_contents($skillPath)
            : '';

        $skillLastUpdatedAt = file_exists($skillPath)
            ? Carbon::createFromTimestamp(filemtime($skillPath))
                ->setTimezone(config('app.timezone', 'UTC'))
            : null;

        $corrections = AiCorrectionLog::query()
            ->with(['ticket', 'agent'])
            ->orderByDesc('created_at')
            ->paginate(20);

        $pendingCount = AiCorrectionLog::query()
            ->where('processed', false)
            ->where('ignore_in_training', false)
            ->count();

        $threshold = (int) config('helpdesk.ai.skill_update_threshold', 5);

        return view('ampp.helpdesk.ai-skill.index', compact(
            'skillContent',
            'skillLastUpdatedAt',
            'corrections',
            'pendingCount',
            'threshold',
        ));
    }
}
