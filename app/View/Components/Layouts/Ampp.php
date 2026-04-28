<?php

namespace App\View\Components\Layouts;

use App\Models\Release;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Ampp extends Component
{
    public ?string $currentReleaseTagName = null;

    public function __construct(
        public ?string $title = null,
        public ?View $breadcrumbs = null
    )
    {
        $appName = config('app.name');

        $this->title = $this->title ? "$this->title | $appName" : $appName;

        $this->currentReleaseTagName = Release::firstWhere('is_current_release', '=', true)?->tag_name;
    }

    public function render(): View
    {
        return view('components.layouts.ampp');
    }
}
