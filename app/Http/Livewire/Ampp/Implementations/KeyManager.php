<?php

namespace App\Http\Livewire\Ampp\Implementations;

use App\Enums\BeaconKeyStatus;
use App\Models\BeaconKey;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class KeyManager extends Component
{
    use LivewireAlert;

    public ?string $newKeyLabel = '';
    public ?string $generatedKey = null;
    public bool $showGenerateModal = false;

    #[On('refreshKeys')]
    public function refreshKeys(): void
    {
        // triggers re-render
    }

    public function generateKey()
    {
        $beaconKey = BeaconKey::generate($this->newKeyLabel ?: null);
        $this->generatedKey = $beaconKey->key;
        $this->newKeyLabel = '';
    }

    public function closeGenerateModal()
    {
        $this->showGenerateModal = false;
        $this->generatedKey = null;
        $this->newKeyLabel = '';
    }

    public function disableKey(int $keyId)
    {
        $key = BeaconKey::findOrFail($keyId);
        $key->disable();

        $this->alert('success', __('Key disabled successfully'));
    }

    public function deleteKey(int $keyId)
    {
        $key = BeaconKey::findOrFail($keyId);

        if ($key->status === BeaconKeyStatus::Claimed) {
            $this->alert('error', __('Cannot delete a claimed key. Disable it first.'));
            return;
        }

        $key->delete();
        $this->alert('success', __('Key deleted'));
    }

    public function render()
    {
        $keys = BeaconKey::with('implementation')
            ->latest()
            ->get();

        return view('livewire.ampp.implementations.key-manager', compact('keys'));
    }
}
