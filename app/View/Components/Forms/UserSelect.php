<?php

namespace App\View\Components\Forms;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserSelect extends Select
{
    public array $photos = [];

    public function __construct(
        public string $name,
        public string $id,
        public array $users = [],
        public string|array|null $values = null,
        public ?string $errorFor = null
    )
    {
        $this->photos = User::whereIn('id', array_keys($this->users))
            ->get()
            ->pluck('profile_photo_url', 'id')
            ->toArray()
        ;

        parent::__construct($this->name, $this->users, $this->values, $this->errorFor);
    }

    public function render(): View
    {
        return view('components.forms.user-select');
    }
}
