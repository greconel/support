<?php

namespace App\Actions\Releases;

use App\Models\Release;
use Illuminate\Support\Facades\Http;

class ImportReleasesAction
{
    public function execute(): void
    {
        $url = config('services.github.url');
        $token = config('services.github.token');
        $repo = config('services.github.project_repo');

        if (! $url){
            return;
        }

        if (! $token){
            return;
        }

        if (! $repo){
            return;
        }

        $response = Http::withHeaders(['Authorization' => "token {$token}"])
            ->get("{$url}/repos/{$repo}/releases");

        if (! $response->ok()){
            return;
        }

        $releases = $response->collect();

        foreach ($releases as $release) {
            Release::updateOrCreate(
                ['tag_name' => $release['tag_name']],
                [
                    'description' => $release['body'],
                    'released_at' => $release['published_at']
                ]
            );
        }
    }
}
