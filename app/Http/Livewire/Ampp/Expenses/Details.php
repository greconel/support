<?php

namespace App\Http\Livewire\Ampp\Expenses;

use App\Enums\ExpenseStatus;
use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class Details extends Component
{
    use AuthorizesRequests;

    public Expense $expense;
    public string $status = '';
    public ?string $paidAt = null;

    protected $rules = [
        'expense.is_approved_for_payment' => ['nullable']
    ];

    public function mount(Expense $expense)
    {
        $this->expense = $expense;
        $this->paidAt = $this->expense->paid_at?->format('Y-m-d');
    }

    public function updateStatus()
    {
        $this->authorize('changeStatus', $this->expense);

        $this->validate([
            'status' => ['required', new Enum(ExpenseStatus::class)]
        ]);

        $this->expense->status = $this->status;

        $this->expense->paid_at = $this->expense->status == ExpenseStatus::Paid
            ? $this->expense->paid_at ?? now()
            : null;

        $this->expense->save();

        $this->paidAt = $this->expense->paid_at?->format('Y-m-d');

        $this->reset('status');
    }

    public function updatedPaidAt()
    {
        $this->validate([
            'paidAt' => ['nullable', 'date']
        ]);

        $this->expense->update(['paid_at' => empty($this->paidAt) ? null : $this->paidAt]);
    }

    public function updatedExpenseIsApprovedForPayment()
    {
        $this->authorize('changeStatus', $this->expense);

        $this->expense->save();
    }

    public function render(): View
    {
        return view('livewire.ampp.expenses.details');
    }
}
