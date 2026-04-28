<div>
    @if($hoursRounded >= 0 && $project->budget_hours && ($hoursRounded / $project->budget_hours) * 100 <= 75)
        <span class="text-success">{{ __(':hours hours', ['hours' => $hoursPrecise]) }}</span>
    @elseif($hoursRounded > 0 && $project->budget_hours && ($hoursRounded / $project->budget_hours) * 100 <= 90)
        <span class="text-warning">{{ __(':hours hours', ['hours' => $hoursPrecise]) }}</span>
    @else
        <span class="text-danger">{{ __(':hours hours', ['hours' => $hoursPrecise]) }}</span>
    @endif
</div>

