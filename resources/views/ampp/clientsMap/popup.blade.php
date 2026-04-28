<div style="min-width: 200px; width: 100%; height: 100%">
    <p>
        <b>
            {{ $client->full_name }}
        </b>
    </p>

    <hr>

    <p>
        {{ __('clients.company') }}: <span class="text-muted">{{ $client->company }}</span> <br>
        {{ __('general.created_at') }}: <span class="text-muted">{{ \Carbon\Carbon::parse($client->created_at)->format('d/m/Y') }}</span>
    </p>

    <hr>

    <p>
        {{ __('clients.street') }}: <span class="text-muted">{{ $client->street }}</span> <br>
        {{ __('clients.number') }}: <span class="text-muted">{{ $client->number }}</span> <br>
        {{ __('clients.postal') }}: <span class="text-muted">{{ $client->postal }}</span> <br>
        {{ __('clients.city') }}: <span class="text-muted">{{ $client->city }}</span> <br>
        {{ __('clients.country') }}: <span class="text-muted">{{ $client->country }}</span> <br>

        <a href="tel:{{ $client->phone }}">{{ $client->phone }}</a> <br>
        <a href="mailto:{{ $client->email }}">{{ $client->email }}</a> <br>
    </p>

    <hr>

    <a href="{{ action(\App\Http\Controllers\Ampp\Clients\EditClientController::class, $client) }}" class="link-primary">
        {{ __('general.edit') }}
    </a>
</div>
