<div class="position-relative">
    <div wire:loading>
        <div
            class="position-absolute start-0 top-0 bg-gray-500 bg-opacity-25 d-flex justify-content-center align-items-center rounded"
            style="width: 100%; height: 100%; z-index: 2; backdrop-filter: blur(5px)"
        >
            <div class="spinner-grow" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <section class="row mb-4">
        <div class="col-md-3">
            <div class="card card-body" style="max-height: 100px; overflow-y: auto">
                <div class="d-flex gap-3">
                    <i class="far fa-clock text-blue-200 fa-3x"></i>

                    <div class="d-flex flex-column">
                        <span class="text-gray-500">{{ __('Total time') }}</span>
                        <div>
                            <span class="fs-2 fw-bolder">{{ $this->totalHours }}</span>
                            <span class="me-1">{{ __('hours') }}</span>
                            <span class="fs-2 fw-bolder">{{ $this->minutes }}</span>
                            <span>{{ __('minutes') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-body" style="max-height: 100px; overflow-y: auto">
                <div class="d-flex gap-3">
                    <i class="fas fa-euro-sign text-green-200 fa-3x"></i>

                    <div class="d-flex flex-column">
                        <span class="text-gray-500">{{ __('Billable time') }}</span>
                        <div>
                            <span class="fs-2 fw-bolder">{{ $this->totalHoursBillable }}</span>
                            <span class="me-1">{{ __('hours') }}</span>
                            <span class="fs-2 fw-bolder">{{ $this->minutesBillable }}</span>
                            <span>{{ __('minutes') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-body" style="max-height: 100px; overflow-y: auto">
                <div class="d-flex gap-3">
                    <i class="fas fa-user text-yellow-200 fa-3x"></i>

                    <div class="d-flex flex-column">
                        <span class="text-gray-500">{{ __('Topmost client') }}</span>
                        <span class="fs-3 fw-bolder">
                            @if($this->topMostClient)
                                {{ $this->topMostClient->full_name_with_company }}
                            @else
                                /
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-body" style="max-height: 100px; overflow-y: auto">
                <div class="d-flex gap-3">
                    <i class="fas fa-tasks text-red-200 fa-3x"></i>

                    <div class="d-flex flex-column">
                        <span class="text-gray-500">{{ __('Busiest project') }}</span>
                        <span class="fs-3 fw-bolder">
                            @if($this->busiestProject)
                                {{ $this->busiestProject->name }}
                            @else
                                /
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <nav>
            <div class="nav nav-tabs gap-3" id="nav-tab" role="tablist">
                <button class="nav-link active position-relative" data-bs-toggle="tab" data-bs-target="#clients" type="button">
                    {{ __('Clients') }}

                    @if($clients->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-blue-200">
                            {{ $clients->count() }}
                        </span>
                    @endif
                </button>

                <button class="nav-link position-relative" data-bs-toggle="tab" data-bs-target="#projects" type="button">
                    {{ __('Projects') }}

                    @if($projects->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-green-200">
                            {{ $projects->count() }}
                        </span>
                    @endif
                </button>

                <button class="nav-link position-relative" data-bs-toggle="tab" data-bs-target="#other" type="button">
                    {{ __('Other') }}

                    @if($otherTimeRegistrations->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-gray-400">
                            {{ $otherTimeRegistrations->count() }}
                        </span>
                    @endif
                </button>

                <button class="nav-link position-relative" data-bs-toggle="tab" data-bs-target="#team" type="button">
                    {{ __('Team') }}

                    @if($users->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-red-200">
                            {{ $users->count() }}
                        </span>
                    @endif
                </button>
            </div>
        </nav>

        <div class="tab-content py-3" id="nav-tabContent">
            <div class="tab-pane fade show active" id="clients">
                <div class="table-responsive">
                    <x-ampp.project-reports.client-table :clients="$clients" :from="$from" :till="$till" />
                </div>
            </div>

            <div class="tab-pane fade" id="projects">
                <div class="table-responsive">
                    <x-ampp.project-reports.project-table :projects="$projects" :from="$from" :till="$till" />
                </div>
            </div>

            <div class="tab-pane fade" id="other">
                <div class="table-responsive">
                    <x-ampp.project-reports.time-registration-table :time-registrations="$otherTimeRegistrations" />
                </div>
            </div>

            <div class="tab-pane fade" id="team">
                <div class="table-responsive">
                    <x-ampp.project-reports.user-table :users="$users" :from="$from" :till="$till" />
                </div>
            </div>
        </div>
    </section>
</div>
