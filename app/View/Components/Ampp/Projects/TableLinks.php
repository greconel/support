<?php

namespace App\View\Components\Ampp\Projects;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableLinks extends Component
{
    public int $active;
    public int $archived;

    public function __construct()
    {
        $this->active = Project::count();
        $this->archived = Project::onlyTrashed()->count();
    }

    public function render(): View
    {
        return view('components.ampp.projects.table-links');
    }
}
