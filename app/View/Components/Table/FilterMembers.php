<?php

namespace App\View\Components\Table;

use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\QueryString\QueryString;

class FilterMembers extends Component
{
    public function __construct(
        public QueryString $queryString,
        public ?User $filteredMember = null,
        public ?string $filteredMemberId = null,
        public ?string $filteredMemberName = null
    ) {
        $this->queryString = new QueryString(urldecode(request()->fullUrl()));
    }

    public function href(): string
    {
        return $this->queryString->toggle('assignee', $this->getId());
    }

    public function isActive(): bool
    {
        return $this->queryString->isActive('assignee', $this->getId());
    }

    public function getId(): string {
        return $this->filteredMemberId ?: $this->filteredMember->id;
    }

    public function getName(): string {
        return $this->filteredMemberName ?: $this->filteredMember->name;
    }

    public function getAvatarUrl(): string {
        return $this->filteredMember->profile_photo_url;
    }

    public function render(): View
    {
        return view('components.table.filter-members');
    }
}
