<?php

namespace App\Http\Livewire\Ampp\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use Livewire\Component;

class Tokens extends Component
{
    public array $scopes = [];

    public string $tokenName = '';
    public array $tokenScopes = [];

    public function mount(TokenRepository $tokenRepository)
    {
        $this->scopes = Passport::scopes()->toArray();
    }

    public function getTokensProperty(TokenRepository $tokenRepository)
    {
        return $tokenRepository->forUser(auth()->id());
    }

    public function create()
    {
        $this->validate([
            'tokenName' => ['required', 'string', 'max:255'],
            'tokenScopes' => ['nullable', 'array', Rule::in(Passport::scopeIds())]
        ]);

        $token = auth()->user()->createToken(
            $this->tokenName, $this->tokenScopes ?: []
        );

        $this->reset('tokenName', 'tokenScopes');

        $this->dispatch('close')->self();

        session()
            ->flash(
                'successToken',
                __("Below is your Personal Access Token, copy and paste it somewhere safe. This is the only time you will see it! <br><br> <pre>{$token->accessToken}</pre>")
            )
        ;
    }

    public function destroy(TokenRepository $tokenRepository, $id)
    {
        $token = $tokenRepository->findForUser(
            $id, auth()->id()
        );

        if (!is_null($token)) {
            $token->revoke();
        }
    }

    public function render(): View
    {
        return view('livewire.ampp.profile.tokens');
    }
}
