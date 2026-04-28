<x-layouts.ampp :title="__('Show help message')" :breadcrumbs="Breadcrumbs::render('showMyHelpMessage', $helpMessage)">
    <div class="card container">
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-xl-2">
                    <p class="fs-4 fw-light">{{ __('Message') }}</p>
                </div>

                <div class="col-xl-10">
                    <h2 class="fw-normal mb-3">{{ $helpMessage->title }}</h2>

                    <hr>

                    <p>{{ $helpMessage->message }}</p>

                    @if($helpMessage->hasMedia('images'))
                        <div class="row">
                            @foreach($helpMessage->getMedia('images') as $media)
                                <div class="col-lg-4">
                                    <a href="{{ action(\App\Http\Controllers\Media\DownloadMediaController::class, $media) }}">
                                        <img src="{{ action(\App\Http\Controllers\Media\ShowMediaController::class, $media) }}" alt="image" class="img-fluid rounded-3 shadow"
                                             style="height: 200px; object-fit: cover">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xl-2">
                    <p class="fs-4 fw-light">{{ __('Responses') }}</p>
                </div>

                <div class="col-xl-10">
                    <p class="fw-light">This feature isn't implemented yet..</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.ampp>
