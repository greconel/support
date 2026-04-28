<x-layouts.ampp>
    <x-ui.page-title>{{ __('My Dashboard') }}</x-ui.page-title>

    <div class="container-fluid">
        <div class="row">
            @can('viewAny', \App\Models\Invoice::class)
                <div class="col-md-3">
                    <x-ui.dashboard-card class="border-blue-500">
                        <div class="d-flex gap-3">
                            <i class="fas fa-euro-sign text-blue-200 fa-3x"></i>

                            <div class="d-flex flex-column">
                                <a class="text-gray-400" href="{{ action(\App\Http\Controllers\Ampp\Invoices\IndexInvoiceController::class) }}">
                                    {{ __('Total open amount that hasn\'t been expired yet') }}
                                </a>

                                <div>
                                    <span class="fs-2 fw-bolder">€ {{ number_format($outstandingInvoicesNotExpiredExclVat, 2, ',', '.') }}</span>
                                    <span>{{ __('excl. VAT') }}</span>
                                    <br>
                                    <span class="fw-bolder">€ {{ number_format($outstandingInvoicesNotExpiredInclVat, 2, ',', '.') }}</span>
                                    <span>{{ __('incl. VAT') }}</span>
                                </div>
                            </div>
                        </div>
                    </x-ui.dashboard-card>
                </div>
            @endcan

            @can('viewAny', \App\Models\Invoice::class)
                <div class="col-md-3">
                    <x-ui.dashboard-card class="border-yellow-500">
                        <div class="d-flex gap-3">
                            <i class="fas fa-euro-sign text-yellow-200 fa-3x"></i>

                            <div class="d-flex flex-column">
                                <a class="text-gray-400" href="{{ action(\App\Http\Controllers\Ampp\Invoices\IndexInvoiceController::class) }}">
                                    {{ __('Total open amount that is expired for a maximum of 30 days') }}
                                </a>

                                <div>
                                    <span class="fs-2 fw-bolder">€ {{ number_format($outstandingInvoicesExpiredForMax30DaysExclVat, 2, ',', '.') }}</span>
                                    <span>{{ __('excl. VAT') }}</span>
                                    <br>
                                    <span class="fw-bolder">€ {{ number_format($outstandingInvoicesExpiredForMax30DaysWithVat, 2, ',', '.') }}</span>
                                    <span>{{ __('incl. VAT') }}</span>
                                </div>
                            </div>
                        </div>
                    </x-ui.dashboard-card>
                </div>
            @endcan

            @can('viewAny', \App\Models\Invoice::class)
                <div class="col-md-3">
                    <x-ui.dashboard-card class="border-blue-500">
                        <div class="d-flex gap-3">
                            <i class="fas fa-euro-sign text-blue-200 fa-3x"></i>

                            <div class="d-flex flex-column">
                                <a class="text-gray-400" href="{{ action(\App\Http\Controllers\Ampp\Invoices\IndexInvoiceController::class) }}">
                                    {{ __('Total open amount that is expired longer then 30 days') }}
                                </a>

                                <div>
                                    <span class="fs-2 fw-bolder">€ {{ number_format($outstandingInvoicesExpiredLongerThen30DaysExclVat, 2, ',', '.') }}</span>
                                    <span>{{ __('excl. VAT') }}</span>
                                    <br>
                                    <span class="fw-bolder">€ {{ number_format($outstandingInvoicesExpiredLongerThen30DaysInclVat, 2, ',', '.') }}</span>
                                    <span>{{ __('incl. VAT') }}</span>
                                </div>
                            </div>
                        </div>
                    </x-ui.dashboard-card>
                </div>
            @endcan

            @can('viewAny', \App\Models\Client::class)
                <div class="col-md-3">
                    <x-ui.dashboard-card class="border-yellow-500">
                        <div class="d-flex gap-3">
                            <i class="fas fa-users text-yellow-200 fa-3x"></i>

                            <div class="d-flex flex-column">
                                <a class="text-gray-400" href="{{ action(\App\Http\Controllers\Ampp\Clients\IndexClientController::class) }}">
                                    {{ __('Clients and contacts') }}
                                </a>

                                <div>
                                    <span class="fs-2 fw-bolder">{{ $totalClients }}</span>
                                    <span>{{ __('clients') }}</span>
                                    <br>
                                    <span class="fw-bolder">{{ $totalContacts }}</span>
                                    <span>{{ __('contacts') }}</span>
                                </div>
                            </div>
                        </div>
                    </x-ui.dashboard-card>
                </div>
            @endcan

            <div class="col-md-3">
                <x-ui.dashboard-card class="border-yellow-500">
                    <div class="d-flex gap-3">
                        <i class="fas fa-tasks text-yellow-200 fa-3x"></i>

                        <div class="d-flex flex-column">
                            <a class="text-gray-400" href="{{ action(\App\Http\Controllers\Ampp\Projects\IndexProjectController::class) }}">
                                {{ __('Projects') }}
                            </a>

                            <div>
                                <span class="fs-2 fw-bolder">{{ $totalProjects }}</span>
                            </div>
                        </div>
                    </div>
                </x-ui.dashboard-card>
            </div>

            <div class="col-md-3">
                <x-ui.dashboard-card class="border-blue-500">
                    <div class="d-flex gap-3">
                        <i class="fas fa-stopwatch text-blue-200 fa-3x"></i>

                        <div class="d-flex flex-column">
                            <a class="text-gray-400" href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class) }}">
                                {{ __('Today\'s time registrations') }}
                            </a>

                            <div>
                                <span class="fs-2 fw-bolder">{{ $timeRegistrationsToday }}</span>
                            </div>
                        </div>
                    </div>
                </x-ui.dashboard-card>
            </div>

            @can('viewAny', \App\Models\Invoice::class)
                <div class="col-md-3">
                    <x-ui.dashboard-card class="border-yellow-500">
                        <div class="d-flex gap-3">
                            <i class="fas fa-file-invoice-dollar text-yellow-200 fa-3x"></i>

                            <div class="d-flex flex-column">
                                <a class="text-gray-400" href="{{ action(\App\Http\Controllers\Ampp\Invoices\IndexInvoiceController::class) }}">
                                    {{ __('Invoices') }}
                                </a>

                                <div>
                                    <span class="fs-2 fw-bolder">{{ $invoicesThisYear }}</span>
                                    <span>{{ __('invoices this year and') }}</span>
                                    <span class="fs-2 fw-bolder">{{ $totalInvoices }}</span>
                                    <span>{{ __('total') }}</span>
                                </div>
                            </div>
                        </div>
                    </x-ui.dashboard-card>
                </div>
            @endcan

            <div class="col-md-3">
                <x-ui.dashboard-card class="border-blue-500">
                    <div class="d-flex gap-3">
                        <i class="fas fa-plug text-blue-200 fa-3x"></i>

                        <div class="d-flex flex-column">
                            <a class="text-gray-400" href="{{ action(\App\Http\Controllers\Ampp\Connections\IndexConnectionController::class) }}">
                                {{ __('AMPP connections') }}
                            </a>

                            <div>
                                <span class="fs-2 fw-bolder">{{ $connectionsInUse }}</span>
                                <span>{{ __('in use and') }}</span>
                                <span class="fs-2 fw-bolder link-blue-500">{{ $totalConnection }}</span>
                                <span>{{ __('total available') }}</span>
                            </div>
                        </div>
                    </div>
                </x-ui.dashboard-card>
            </div>
        </div>

        {{-- Connections --}}
        <div class="row">
            <div class="col-xxl-6">
                <x-ui.dashboard-card class="border-danger">
                    <div class="lead mb-3">{{ __('Connections') }}</div>

                    <table class="table table-sm table-borderless table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Connection') }}</th>
                                <th>{{ __('Location') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Runtime') }}</th>
                                <th>{{ __('Latest activity') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Adsolut</td>
                                <td>Office server room</td>
                                <td>Clients export to AMPP</td>
                                <td>Every minute</td>
                                <td>
                                    <span class="text-warning">{{ __('Never') }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Clearfacts</td>
                                <td>Cloud</td>
                                <td>Invoice import from AMPP</td>
                                <td>At invoice status <i>paid</i></td>
                                <td>
                                <span class="text-success">
                                    2 hours ago
                                </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </x-ui.dashboard-card>
            </div>
        </div>

        {{-- Charts --}}
        <div class="row">
            @can('viewAny', \App\Models\Client::class)
                <div class="col-lg-6">
                    <x-ui.dashboard-card class="border-primary">
                        <canvas id="clientChart"></canvas>
                    </x-ui.dashboard-card>
                </div>
            @endcan

            @can('viewAny', \App\Models\Invoice::class)
                <div class="col-lg-6">
                    <x-ui.dashboard-card class="border-primary">
                        <canvas id="invoicesChart"></canvas>
                    </x-ui.dashboard-card>
                </div>
            @endcan
        </div>
    </div>

    <x-push name="scripts">
        <script>
            // client chart
            const clientChart = new Chart(
                document.getElementById('clientChart'),
                {
                    type: 'line',
                    data: {
                        labels: [
                            "{{ __('January') }}",
                            "{{ __('February') }}",
                            "{{ __('March') }}",
                            "{{ __('April') }}",
                            "{{ __('May') }}",
                            "{{ __('June') }}",
                            "{{ __('July') }}",
                            "{{ __('August') }}",
                            "{{ __('September') }}",
                            "{{ __('October') }}",
                            "{{ __('November') }}",
                            "{{ __('December') }}"
                        ],
                        datasets: [{
                            label: '{{ __('Customer growth') }} ({{ date('Y') }})',
                            backgroundColor: '#007BFF',
                            borderColor: '#007BFF',
                            data: [
                                @php
                                    $startMonth = 1;
                                    $endMonth = date('m');

                                    for ($i = 1; $i <= $endMonth; $i++){
                                        if (\Illuminate\Support\Arr::exists($clients, sprintf('%02d', $i))){
                                            echo $clients[sprintf('%02d', $i)]->count() . ',';
                                            continue;
                                        }

                                        echo 0 . ',';
                                    }
                                @endphp
                            ],
                        }]
                    },
                    options: {
                        locale: 'nl-NL',
                        scales: {
                            y: {
                                suggestedMin: 0,
                                min: 0,
                            }
                        }
                    }
                }
            );

            const invoicesChart = new Chart(
                document.getElementById('invoicesChart'),
                {
                    type: 'line',
                    data: {
                        labels: [
                            "{{ __('January') }}",
                            "{{ __('February') }}",
                            "{{ __('March') }}",
                            "{{ __('April') }}",
                            "{{ __('May') }}",
                            "{{ __('June') }}",
                            "{{ __('July') }}",
                            "{{ __('August') }}",
                            "{{ __('September') }}",
                            "{{ __('October') }}",
                            "{{ __('November') }}",
                            "{{ __('December') }}"
                        ],
                        datasets: [{
                            label: '{{ __('Invoices growth') }} ({{ date('Y') }})',
                            backgroundColor: '#007BFF',
                            borderColor: '#007BFF',
                            data: [
                                @php
                                    $startMonth = 1;
                                    $endMonth = date('m');

                                    for ($i = 1; $i <= $endMonth; $i++){
                                        if (\Illuminate\Support\Arr::exists($invoices, sprintf('%02d', $i))){
                                            echo $invoices[sprintf('%02d', $i)]->count() . ',';
                                            continue;
                                        }

                                        echo 0 . ',';
                                    }
                                @endphp
                            ],
                        }]
                    },
                    options: {
                        locale: 'nl-NL',
                        scales: {
                            y: {
                                suggestedMin: 0,
                                min: 0,
                            }
                        }
                    }
                }
            );
        </script>
    </x-push>
</x-layouts.ampp>
