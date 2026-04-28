<x-layouts.ampp :title="__('Releases')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All available releases') }}</x-ui.page-title>

        @can('create', \App\Models\Release::class)
            <a href="{{ action(\App\Http\Controllers\Ampp\Releases\ImportReleasesController::class) }}" class="btn btn-primary">
                {{ __('Import latest releases') }}
            </a>
        @endcan
    </div>

    @foreach($releases as $release)
        <div class="mb-5">
            <div class="mb-3">
                <h2 class="fw-bolder">
                    {{ $release->tag_name }}
                </h2>

                <p class="small">
                    <span>
                        {{ __('Released at:') }}
                        {{ $release->released_at?->format('d/m/Y') }}
                    </span>

                    @if($release->is_current_release)
                        <span class="text-success fw-bolder">
                            {{ __('Current release') }}
                        </span>
                    @elseif(auth()->user()->can('update', $release))
                        <a
                            href="{{ action(\App\Http\Controllers\Ampp\Releases\MarkReleaseAsCurrentReleaseController::class, $release) }}"
                        >
                            {{ __('Mark as current release') }}
                        </a>
                    @endif
                </p>
            </div>

            <div>
                {!! \Illuminate\Support\Str::markdown($release->description) !!}
            </div>
        </div>
    @endforeach
</x-layouts.ampp>
