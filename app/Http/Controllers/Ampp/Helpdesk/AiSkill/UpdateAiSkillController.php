<?php

namespace App\Http\Controllers\Ampp\Helpdesk\AiSkill;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateAiSkillController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('manage ai skill');

        $validated = $request->validate([
            'skill_content' => 'required|string',
        ]);

        $skillPath = storage_path(config('helpdesk.ai.skill_path', 'ai-skill/labeling-skill.md'));
        $backupDir = dirname($skillPath) . '/backups';

        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        if (file_exists($skillPath)) {
            copy(
                $skillPath,
                $backupDir . '/skill-' . now()->format('Y-m-d-His') . '-manual.md',
            );
        }

        $content = $validated['skill_content'];
        $content = preg_replace_callback(
            '/\*\*Versie:\*\*\s*v(\d+)\.(\d+)/m',
            fn ($m) => '**Versie:** v' . $m[1] . '.' . ($m[2] + 1),
            $content,
        );
        $content = preg_replace(
            '/\*\*Laatst bijgewerkt:\*\*.*$/m',
            '**Laatst bijgewerkt:** ' . now()->format('Y-m-d'),
            $content,
        );

        if (! is_dir(dirname($skillPath))) {
            mkdir(dirname($skillPath), 0755, true);
        }

        file_put_contents($skillPath, $content);

        session()->flash('success', __('Skill file saved and version bumped. Backup stored.'));

        return back();
    }
}
