<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        {{ __('Contacts') }}

        <a href="{{ action(\App\Http\Controllers\Ampp\ClientContacts\CreateClientContactController::class, $client) }}" class="link-primary">
            {{ __('Create new contact') }}
        </a>
    </div>

    <div class="card-body">
        @if($client->contacts->count())
            <div class="table-responsive">
                <table class="table table-borderless table-hover cursor-pointer">
                    <thead>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Tags') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th></th>
                    </thead>

                    <tbody>
                        @foreach($client->contacts as $contact)
                            <tr
                                x-data="{ url: {{ json_encode(action(\App\Http\Controllers\Ampp\ClientContacts\ShowClientContactController::class, $contact)) }} }"
                                @dblclick="window.location = url"
                            >
                                <td style="white-space: nowrap">
                                    <a
                                        href="{{ action(\App\Http\Controllers\Ampp\ClientContacts\ShowClientContactController::class, $contact) }}"
                                    >
                                        {{ $contact->full_name }}
                                    </a>
                                </td>
                                <td>
                                    @if($contact->tags)
                                        @foreach($contact->tags as $tag)
                                            <span class="badge bg-gray-600 m-1">{{ $tag }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td style="white-space: nowrap"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                                <td style="white-space: nowrap"><a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-muted mb-0">{{ __('Nothing to see here..') }}</p>
        @endif
    </div>
</div>
