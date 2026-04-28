<div>
    <p class="text-muted">{{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}</p>

    @foreach ($this->sessions as $session)
        <div class="d-flex align-items-center mb-4">
            <div>
                @if ($session->agent->isDesktop())
                    <i class="fas fa-desktop fa-2x me-3"></i>
                @else
                    <i class="fas fa-mobile-alt fa-2x me-3"></i>
                @endif
            </div>

            <div class="ml-3">
                <div class="small text-muted">
                    {{ $session->agent->platform() }} - {{ $session->agent->browser() }}
                </div>

                <div>
                    <div>
                        {{ $session->ip_address }},

                        @if ($session->is_current_device)
                            <span class="text-success">{{ __('This device') }}</span>
                        @else
                            {{ __('Last active') }} {{ $session->last_active }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmPasswordModal">
        {{ __('Log Out Other Browser Sessions') }}
    </button>

    <div
        class="modal fade"
        id="confirmPasswordModal"
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
                    <h5 class="modal-title">{{ __('Log Out Other Browser Sessions') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>
                        {{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}
                    </p>

                    <x-forms.label for="password">{{ __('Password') }}</x-forms.label>
                    <x-forms.input type="password" name="password" wire:model.lazy="password" />
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="logOutOtherDevices">{{ __('Log Out Other Browser Sessions') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<x-push name="scripts">
    <script>
        Livewire.on('toggleConfirmPasswordModal', function() {
            confirmPasswordModal.toggle();
        })
    </script>
</x-push>
