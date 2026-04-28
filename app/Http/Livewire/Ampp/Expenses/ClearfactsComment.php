<?php

namespace App\Http\Livewire\Ampp\Expenses;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ClearfactsComment extends Component
{
    public Expense $expense;

    public function mount(Expense $expense)
    {
        $this->expense = $expense;
    }

    #[On('refreshExpense')]
    public function refreshExpense()
    {
        $this->expense->refresh();
    }

    public function render(): View
    {
        return view('livewire.ampp.expenses.clearfacts-comment');
    }
}
