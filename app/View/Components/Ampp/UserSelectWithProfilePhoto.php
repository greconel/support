<?php

namespace App\View\Components\Ampp;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserSelectWithProfilePhoto extends Component
{
    public array $users = [];
    public array $userProfilePhotos = [];
    public string $id;
    public bool $multiple;

    public function __construct(array $users, string $id, bool $multiple = false)
    {
        $this->users = $users;
        $this->id = $id;
        $this->multiple = $multiple;

        $this->userProfilePhotos = User::whereIn('id', $users)->pluck('profile_photo_url', 'id')->toArray();
    }

    public function render(): View
    {
        return view('components.ampp.user-select-with-profile-photo');
    }
}
