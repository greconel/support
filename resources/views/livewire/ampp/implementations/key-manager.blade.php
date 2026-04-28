<div>
    {{-- Generate new key --}}
    <div class="mb-4 p-3 bg-gray-50 rounded">
        @if($generatedKey)
            <div class="alert alert-success mb-0">
                <div class="fw-bold mb-1">
                    <i class="fas fa-check-circle me-1"></i> {{ __('Key generated — copy it now, it won\'t be shown again!') }}
                </div>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <code id="beacon-key-output" class="bg-white p-2 rounded flex-grow-1 user-select-all" style="font-size: 0.8rem; word-break: break-all;">{{ $generatedKey }}</code>
                    <button class="btn btn-sm btn-outline-success" id="beacon-key-copy">
                        <i class="fas fa-copy"></i>
                    </button>
                    <script>
                        document.getElementById('beacon-key-copy').addEventListener('click', function() {
                            var code = document.getElementById('beacon-key-output');
                            var range = document.createRange();
                            range.selectNodeContents(code);
                            var sel = window.getSelection();
                            sel.removeAllRanges();
                            sel.addRange(range);
                            document.execCommand('copy');
                            sel.removeAllRanges();
                            this.innerHTML = '<i class="fas fa-check"></i>';
                        });
                    </script>
                </div>
                <button class="btn btn-sm btn-outline-secondary mt-2" wire:click="closeGenerateModal">
                    {{ __('Done') }}
                </button>
            </div>
        @else
            <div class="d-flex gap-2 align-items-end">
                <div class="flex-grow-1">
                    <label class="form-label small fw-medium">{{ __('Label (optional)') }}</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model.defer="newKeyLabel"
                           placeholder="{{ __('e.g. Client X production') }}">
                </div>
                <button class="btn btn-primary btn-sm" wire:click="generateKey">
                    <i class="fas fa-plus me-1"></i> {{ __('Generate Key') }}
                </button>
            </div>
        @endif
    </div>

    {{-- Key list --}}
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="small">{{ __('Key') }}</th>
                    <th class="small">{{ __('Label') }}</th>
                    <th class="small">{{ __('Status') }}</th>
                    <th class="small">{{ __('Linked to') }}</th>
                    <th class="small">{{ __('Created') }}</th>
                    <th class="small text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($keys as $key)
                    <tr>
                        <td class="small font-monospace text-muted">{{ $key->masked_key }}</td>
                        <td class="small">{{ $key->label ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $key->status->color() }}">{{ $key->status->label() }}</span>
                        </td>
                        <td class="small">
                            @if($key->implementation)
                                <a href="{{ action(\App\Http\Controllers\Ampp\Implementations\ShowImplementationController::class, $key->implementation) }}">
                                    {{ $key->implementation->name }}
                                </a>
                            @else
                                —
                            @endif
                        </td>
                        <td class="small text-muted">{{ $key->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            @if($key->status !== \App\Enums\BeaconKeyStatus::Disabled)
                                <button class="btn btn-sm btn-outline-warning py-0 px-1"
                                        wire:click="disableKey({{ $key->id }})"
                                        onclick="return confirm('{{ __('Disable this key?') }}')"
                                        title="{{ __('Disable') }}">
                                    <i class="fas fa-ban" style="font-size: 0.7rem;"></i>
                                </button>
                            @endif
                            @if($key->status !== \App\Enums\BeaconKeyStatus::Claimed)
                                <button class="btn btn-sm btn-outline-danger py-0 px-1"
                                        wire:click="deleteKey({{ $key->id }})"
                                        onclick="return confirm('{{ __('Delete this key permanently?') }}')"
                                        title="{{ __('Delete') }}">
                                    <i class="fas fa-trash" style="font-size: 0.7rem;"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted small py-3">
                            {{ __('No keys generated yet') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
