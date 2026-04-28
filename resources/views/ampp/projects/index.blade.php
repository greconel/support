<x-layouts.ampp :title="__('Projects')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All projects') }}</x-ui.page-title>

        @can('create', \App\Models\Project::class)
            <x-ui.split-button>
                <x-slot name="button">
                    <a href="{{ action(\App\Http\Controllers\Ampp\Projects\CreateProjectController::class) }}" class="btn btn-primary">
                        {{ __('Create new project') }}
                    </a>
                </x-slot>

                <x-slot name="dropdown">
                    <li>
                        <a href="{{ action(\App\Http\Controllers\Ampp\Projects\ExportProjectsController::class) }}" class="dropdown-item">
                            {{ __('Export projects') }}
                        </a>
                    </li>
                </x-slot>
            </x-ui.split-button>
        @else
            <a href="{{ action(\App\Http\Controllers\Ampp\Projects\ExportProjectsController::class) }}" class="btn btn-primary">
                {{ __('Export projects') }}
            </a>
        @endcan
    </div>

    <div class="row justify-content-center mb-4">
        <div class="col-md-3">
            <x-ui.info-card>
                <x-slot name="title">
                    {{ __('Total projects') }}
                </x-slot>

                <x-slot name="icon">
                    <i class="fas fa-clipboard-list fa-3x" style="color: #818CF8"></i>
                </x-slot>

                <x-slot name="text">
                    <span class="fs-3 fw-bolder">{{ $totalProjects }}</span>
                </x-slot>
            </x-ui.info-card>
        </div>

        <div class="col-md-3">
            <x-ui.info-card>
                <x-slot name="title">
                    {{ __('Total hours') }}
                </x-slot>

                <x-slot name="icon">
                    <i class="far fa-clock text-blue-200 fa-3x"></i>
                </x-slot>

                <x-slot name="text">
                    <span class="fs-3 fw-bolder">{{ $totalHours }}</span>
                </x-slot>
            </x-ui.info-card>
        </div>

        <div class="col-md-3">
            <x-ui.info-card>
                <x-slot name="title">
                    {{ __('Total unfinished todo\'s') }}
                </x-slot>

                <x-slot name="icon">
                    <i class="fas fa-list text-green-200 fa-3x"></i>
                </x-slot>

                <x-slot name="text">
                    <span class="fs-3 fw-bolder">{{ $totalTodos }}</span>
                </x-slot>
            </x-ui.info-card>
        </div>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <div class="row w-100">
                <div class="col-md-6">
                    <x-table.filter table-id="project-table" :placeholder="__('Search projects...')" />
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-wrap">
                        <x-table.filter-members :filtered-member-id="'team'" :filtered-member-name="'Team'" ></x-table.filter-members>

                        @foreach(App\Models\User::all() as $user)
                            <x-table.filter-members :filteredMember="$user"></x-table.filter-members>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="dataTable-filters">
                @foreach(\App\Enums\ProjectCategory::cases() as $category)
                    <x-table.filters-option
                        state="category"
                        :value="$category->value"
                        :description="$category->description()"
                        :count="\App\Models\Project::where('category', '=', $category)->count()"
                    >
                        {{ $category->label()  }}
                    </x-table.filters-option>
                @endforeach

                <x-table.filters-option state="archive" value="true" :count="$archiveCount">
                    {{ __('Archive') }}
                </x-table.filters-option>
            </div>
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="modals">
        <livewire:ampp.projects.description-edit-modal />
    </x-push>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="project-table" :redirect="action(\App\Http\Controllers\Ampp\Projects\ShowProjectOverviewController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
