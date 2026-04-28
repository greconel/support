<div class="text-center mb-4">
    <x-ui.link :href="action(\App\Http\Controllers\Ampp\Projects\IndexProjectController::class)">
        {{ __('Active') }} ({{ $active }})
    </x-ui.link>
    |
    <x-ui.link :href="action(\App\Http\Controllers\Ampp\Projects\IndexProjectController::class, ['status' => 'archived'])">
        {{ __('Archive') }} ({{ $archived }})
    </x-ui.link>
</div>
