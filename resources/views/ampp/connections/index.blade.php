<x-layouts.ampp>
    <h1 class="mb-3 text-center">{{ __('All connections') }}</h1>

    <div class="container">
        <div class="row">
            @foreach($connections as $connection)
                <div class="col-md-3">
                    <div class="p-3 d-flex flex-column card card-body">
                        <div class="d-flex justify-content-center align-items-center flex-grow-1">
                            {{ $connection->getFirstMedia('logo')->img()->attributes(['class' => 'img-fluid rounded-3']) }}
                        </div>

                        <div class="border-top mt-3 pt-3">
                            <div class="text-center fs-4 fw-bolder mb-1">{{ $connection->name }}</div>

                            <div class="text-center text-muted mb-2">{{ $connection->short_description }}</div>

                            <div class="d-flex justify-content-center">
                                @can('connections.update')
                                    <div class="form-check form-switch">
                                        <x-forms.form
                                            action="{{ action(\App\Http\Controllers\Ampp\Connections\UpdateConnectionController::class, $connection) }}"
                                            method="patch"
                                            id="form_{{ $connection->id }}"
                                        >
                                            <input class="form-check-input p-0 mr-0" type="checkbox" name="in_use" value="1" id="switch_{{ $connection->id }}" {{ $connection->in_use ? 'checked' : '' }}
                                            onchange="document.getElementById('form_{{ $connection->id }}').submit()">
                                        </x-forms.form>
                                    </div>
                                @else
                                    <span @class(['small', 'text-success' => $connection->in_use, 'text-danger' => !$connection->in_use])>
                                        {{ $connection->in_use ? __('Active') : __('Not active') }}
                                    </span>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-light border rounded-3 p-4 mb-4">
            {!! $content !!}
        </div>
    </div>
</x-layouts.ampp>
