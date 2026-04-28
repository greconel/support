<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('showProjectOverview', $project)">
    <x-ampp.projects.layout :project="$project">
        <div class="data-subtitle">{{ __('Details') }}</div>

        @if($project->client->id)
            <div class="data-row">
                <span>{{ __('Client') }}</span>

                <a href="{{ action(\App\Http\Controllers\Ampp\Clients\ShowClientController::class, $project->client) }}">
                    {{ $project->client->full_name }}
                </a>
            </div>
        @endif

        <div class="data-row">
            <span>{{ __('Category') }}</span>
            {{ $project->category?->label() ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Budget') }}</span>
            € {{ $project->budget_money ?? '/' }}
        </div>

        <div class="data-row">
            <span>{{ __('Budget in hours') }}</span>
            {{ $project->budget_hours }}
        </div>

        <div class="data-row">
            <span>{{ __('Current total time') }}</span>
            <livewire:ampp.projects.total-time :project="$project" />
        </div>

        <div class="data-row">
            <span>{{ __('Project started at') }}</span>

            <div>
                {{ $project->created_at->format('d/m/Y') }}
                <span class="small text-muted">({{ $project->created_at->diffForHumans() }})</span>
            </div>
        </div>

        <div class="data-subtitle">{{ __('Members') }}</div>

        <div class="d-flex flex-wrap gap-3">
            @foreach($users as $user)
                <div class="">
                    <div class="card card-body">
                        <div class="d-flex flex-column gap-2 align-items-center">
                            <img
                                src="{{ $user->profile_photo_url }}"
                                alt="avatar"
                                class="img-fluid rounded-circle"
                                style="width: 100px; height: 100px; object-fit: cover"
                            >

                            <span class="fw-bolder">
                                <a href="{{ action(\App\Http\Controllers\Admin\Users\ShowUserController::class, $user) }}">
                                    {{ $user->name }}
                                </a>
                            </span>

                            <span>
                                <a href="mailto:{{ $user->email }}" class="link-gray-600">
                                    {{ $user->email }}
                                </a>
                            </span>

                            <span class="text-gray-500">
                                <i class="fas fa-clock"></i>
                                {{ $user->total_hours }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($project->description)
            <div class="mt-4">
                <hr>
                <x-ui.quill-display :content="$project->description" style="height: auto" />
            </div>
        @endif
    </x-ampp.projects.layout>
</x-layouts.ampp>
