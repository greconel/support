<?php

namespace App\Jobs\DataImport;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportProjectsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $projects = DB::connection('old_ampp')->table('projects')->get();

        foreach ($projects as $project) {
            Project::unguard();

            $newProject = Project::create([
                'id' => $project->id,
                'client_id' => $project->client_id,
                'name' => $project->name,
                'color' => $this->randomColor(),
                'budget_money' => null,
                'budget_hours' => $project->budget,
                'description' => $project->description,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
                'deleted_at' => $project->archive ? now() : null
            ]);

            Project::reguard();

            $projectUsers = DB::connection('old_ampp')
                ->table('project_user')
                ->where('project_id', '=', $project->id)
                ->get();

            foreach ($projectUsers as $projectUser){
                $newProject->users()->attach($projectUser->user_id);
            }
        }
    }

    private function randomColor(): string
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
