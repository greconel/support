<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb text-lowercase">
            @foreach($breadcrumbs as $b)

                @if($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $b['name'] }}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $b['url'] }}">{{ $b['name'] }}</a></li>
                @endif

            @endforeach
        </ol>
    </nav>
</div>
