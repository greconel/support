<?php

namespace App\Http\Livewire\Ampp\Expenses;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ClearfactsCommentModal extends Component
{
    public Expense $expense;

    protected $rules = [
        'expense.clearfacts_comment' => ['nullable', 'string']
    ];

    public function mount(Expense $expense)
    {
        $this->expense = $expense;
    }

    public function updateClearfactsComment()
    {
        $this->validate();
        $this->expense->save();

        $this->dispatch('close')->self();
        $this->dispatch('refreshQuill')->self();
        $this->dispatch('refreshExpense')->to(ClearfactsComment::class);
    }

    public function render(): View
    {
        return view('livewire.ampp.expenses.clearfacts-comment-modal');
    }
}
