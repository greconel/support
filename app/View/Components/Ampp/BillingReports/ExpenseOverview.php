<?php

namespace App\View\Components\Ampp\BillingReports;

use App\Models\Expense;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ExpenseOverview extends Component
{
    public function __construct(
        public Collection $expensesGrouped,
        public string $orderBy
    ){}

    public function getSupplier(int $id): Supplier
    {
        return Supplier::find($id);
    }

    public function calculateTotal(Collection $expensesGroupedBySupplierId)
    {
        $total = 0;

        foreach ($expensesGroupedBySupplierId as $expense){
            $total += $expense->sum('amount_excluding_vat');
        }

        return $total;
    }

    public function calculateTotalWithVat(Collection $expensesGroupedBySupplierId)
    {
        $total = 0;

        foreach ($expensesGroupedBySupplierId as $expense){
            $total += $expense->sum('amount_including_vat');
        }

        return $total;
    }

    /**
     * @return mixed
     * @var Collection<Expense> $expenses
     */
    public function calculateTotalForSupplier(Collection $expenses)
    {
        return $expenses->sum('amount_excluding_vat');
    }

    /**
     * @return mixed
     * @var Collection<Expense> $expenses
     */
    public function calculateTotalWithVatForSupplier(Collection $expenses)
    {
        return $expenses->sum('amount_including_vat');
    }

    public function render(): View
    {
        return view('components.ampp.billing-reports.expense-overview');
    }
}
