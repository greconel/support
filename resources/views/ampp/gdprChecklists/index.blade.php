<x-layouts.ampp :title="__('GDPR checklist')">
    <div class="container">
        <x-ui.page-title>{{ __('GDPR checklist') }}</x-ui.page-title>

        <p class="lead">
            {!! __('After going through this checklist, you are normally sufficiently privacy compliant. We certainly recommend that you properly document and save all your steps and actions, for example in our <a href=":url">audit tool</a>.', ['url' => '#']) !!}
        </p>

        <div class="d-flex justify-content-center">
            <a
                href="{{ action(\App\Http\Controllers\Ampp\GdprChecklists\EditGdprChecklistController::class, \App\Models\GdprChecklist::firstOrCreate()) }}"
                class="btn btn-primary"
            >
                Open checklist
            </a>
        </div>
    </div>
</x-layouts.ampp>
