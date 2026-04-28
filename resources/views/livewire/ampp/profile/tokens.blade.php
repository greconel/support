<div>
    <p class="text-muted">
        {{ __('Manage your personal access tokens here to connect with our API. If you think one of your tokens is compromised, do not hesitate and immediately delete the token.') }}
    </p>

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTokenModal">
            {{ __('Create new token') }}
        </button>
    </div>

    <x-ui.session-alert session="successToken" class="alert-success" />

    <table class="table table-hover">
        <thead>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Scopes') }}</th>
            <th>{{ __('Created at') }}</th>
            <th>{{ __('Expires at') }}</th>
            <th>{{ __('Revoked') }}</th>
            <th></th>
        </thead>

        <tbody>
            @foreach($this->tokens->sortBy('created_at') as $token)
                <tr>
                    <td>{{ $token->name }}</td>
                    <td>
                        @foreach($token->scopes as $scope)
                            {{ Arr::get(collect($scopes)->firstWhere('id', $scope), 'description') }}

                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                    <td>{{ \Illuminate\Support\Carbon::parse($token->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($token->expires_at)->format('d/m/Y H:i') }}</td>
                    <td class="text-danger">{{ $token->revoked ? __('Revoked') : null }}</td>
                    <td>
                        <button class="btn btn-link link-danger {{ $token->revoked ? 'disabled' : null }}" wire:click="destroy('{{ $token->id }}')">
                            {{ __('Revoke') }}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div
        class="modal fade"
        id="createTokenModal"
        tabindex="-1"
        aria-hidden="true"
        wire:ignore.self
        x-data="{ modal: new bootstrap.Modal($refs.modal) }"
        x-ref="modal"
        x-init="$wire.on('close', () => modal.hide())"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Create new token') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="tokenName">{{ __('Name') }}</x-forms.label>
                            <x-forms.input name="tokenName" wire:model.lazy="tokenName" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            @foreach($scopes as $index => $scope)
                                <x-forms.checkbox name="tokenScopes" id="scope_{{ $index }}" :value="$scope['id']" wire:model.lazy="tokenScopes">
                                    {{ $scope['description'] }}
                                </x-forms.checkbox>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="create">{{ __('Create') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<x-push name="scripts">
    <script>
        Livewire.on('toggleCreateTokenModal', () => {
            createTokenModal.toggle();
        });
    </script>
</x-push>
