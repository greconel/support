<?php

namespace App\Http\Livewire\Ampp\Deals;

use App\Http\Controllers\Ampp\Deals\EditDealController;
use App\Models\Deal;
use App\Models\DealColumn;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateDealModal extends Component
{
    public DealColumn $dealColumn;
    public ?Deal $deal = null;

    protected $rules = [
        'deal.name' => ['required', 'string', 'max:255']
    ];

    #[On('createDeal')]
    public function create(DealColumn $dealColumn)
    {
        $this->dealColumn = $dealColumn;
        $this->deal = new Deal();

        $this->dispatch('toggle')->self();
    }

    public function store()
    {
        $this->validate();

        $this->deal->deal_column_id = $this->dealColumn->id;
        $this->deal->save();

        return redirect()->action(EditDealController::class, $this->deal);
    }

    public function render(): View
    {
        return view('livewire.ampp.deals.create-deal-modal');
    }
}
