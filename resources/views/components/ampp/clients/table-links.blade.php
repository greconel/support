<div class="text-center mb-4">
    <x-ui.link :href="action(\App\Http\Controllers\Ampp\Clients\IndexClientController::class)">
        {{ __('Active') }} ({{ $active }})
    </x-ui.link>
    |
    <x-ui.link :href="action(\App\Http\Controllers\Ampp\Clients\IndexClientController::class, ['status' => 'archived'])">
        {{ __('Archived') }} ({{ $archived }})
    </x-ui.link>
</div>

