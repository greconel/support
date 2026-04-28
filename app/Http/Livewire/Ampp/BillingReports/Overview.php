<?php

namespace App\Http\Livewire\Ampp\BillingReports;

use App\Enums\VariousTransactionCategory;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class Overview extends Component
{
    /** @var Carbon|null $from */
    public $from;

    /** @var Carbon|null $till */
    public $till;

    /** @var Collection<Invoice> $invoices */
    public Collection $invoices;

    /** @var Collection<Expense> $expenses */
    public Collection $expenses;
    public Collection $expensesVAT;
    public Collection $expensesTotal;
    public Collection $cashInTotal;

    /** @var Collection<Client> $clients */
    public Collection $clients;

    public float $totalInvoiceAmount = 0;

    public float $totalInvoiceAmountWithVat = 0;
    public float $averageMonthlyInvoicing = 0;

    public float $totalExpenseAmount = 0;

    public float $totalExpenseAmountWithVat = 0;
    public float $totalExpenseVATCategory = 0;
    public float $totalExpenseAmountIncl = 0;
    public ?Client $topMostClient = null;

    public float $topMostClientAmount = 0;

    public ?Supplier $topMostSupplier = null;

    public float $topMostSupplierAmount = 0;

    public float $operationalResult = 0;

    public float $operationalMargin = 0;
    public float $cashFlow = 0;
    public float $cashFlowMargin = 0;

    public string $orderBy = 'year';

    public function mount()
    {
        try {
            $this->from = request()->input('from') ? Carbon::parse(request()->input('from')) : now()->subMillennia(2);
            $this->till = request()->input('till') ? Carbon::parse(request()->input('till')) : now()->addMillennia(2);
        } catch (\Exception) {
            $this->from = now()->subMillennia(2);
            $this->till = now()->addMillennia(2);
        }

        $this->report();
    }

    public function report()
    {
        /* Calculations with invoices */

        $invoices = Invoice::query()
            ->whereBetween('custom_created_at', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->orderByDesc('number')
            ->get()
        ;

        $cashInTotal = Invoice::query()
            ->whereBetween('paid_at', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->orderByDesc('number')
            ->get()
        ;

        $this->totalInvoiceAmount = $invoices->sum('amount');

        $this->totalInvoiceAmountWithVat = $invoices->sum('amount_with_vat');

        $years = Invoice::query()->whereBetween('custom_created_at', [
            $this->from->copy()->startOfDay(),
            $this->till->copy()->endOfDay()
        ])
            ->select(DB::raw('YEAR(custom_created_at) year, MONTH(custom_created_at) month'))
            ->groupBy('year','month')
            ->get()->toArray();

        if(count($years) > 0) {
            $amountOfMonths = 0;
            foreach($years as $year) {
                $amountOfMonths += count($year);
            }

             $this->averageMonthlyInvoicing = $this->totalInvoiceAmountWithVat / $amountOfMonths;
        } else {
            $this->averageMonthlyInvoicing = 0;
        }



        $this->getTopMostClient($invoices);

        /* Calculations with expenses */

        $expensesTotal = Expense::query()
            ->whereBetween('invoice_date', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->orderByDesc('number')
            ->get()
        ;

        $expensesExclVATCategory = Expense::query()
            ->whereBetween('invoice_date', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->where('various_transaction_category', '!=', '0')
            ->orderByDesc('number')
            ->get()
        ;

        $expensesVATCategory = Expense::query()
            ->whereBetween('invoice_date', [
                $this->from->copy()->startOfDay(),
                $this->till->copy()->endOfDay()
            ])
            ->where('various_transaction_category', '=', '0')
            ->orderByDesc('number')
            ->get()
        ;

        $this->totalExpenseVATCategory = $expensesVATCategory->filter(function (Expense $expense) {
            if ($expense->supplier->is_general && $expense->various_transaction_category?->value == VariousTransactionCategory::Vat){
                return false;
            }

            if ($expense->supplier->is_general && $expense->various_transaction_category?->value == VariousTransactionCategory::BankCharges){
                return false;
            }

            return true;
        })
            ->sum('amount_excluding_vat');

        $this->totalExpenseAmount = $expensesExclVATCategory
            ->filter(function (Expense $expense) {
                if ($expense->supplier->is_general && $expense->various_transaction_category?->value == VariousTransactionCategory::Vat){
                    return false;
                }

                if ($expense->supplier->is_general && $expense->various_transaction_category?->value == VariousTransactionCategory::BankCharges){
                    return false;
                }

                return true;
            })
            ->sum('amount_excluding_vat');

        $this->totalExpenseAmountWithVat = $expensesExclVATCategory
            ->filter(fn(Expense $expense) => ! $expense->supplier->is_general)
            ->sum('amount_including_vat');

        $this->totalExpenseAmountIncl = $this->totalExpenseAmountWithVat + $this->totalExpenseVATCategory;

        $this->getTopMostSupplier($expensesExclVATCategory);

        /* Calculations with both invoices and expenses */

        $this->operationalResult = (
            $invoices->sum('amount') -
            $expensesExclVATCategory->filter(function (Expense $expense) {
                if ($expense->supplier->is_general && $expense->various_transaction_category?->value == VariousTransactionCategory::Vat){
                    return false;
                }

                return true;
            })->sum('amount_excluding_vat')
        );

        $this->operationalMargin = $invoices->sum('amount') != 0
            ? ($this->operationalResult / $invoices->sum('amount')) * 100
            : 0;

        $this->cashFlow = $invoices->sum('amount_with_vat') - $this->totalExpenseAmountIncl;
        $this->cashFlowMargin = $invoices->sum('amount_with_vat') != 0 ?
            $this->cashFlow / $invoices->sum('amount_with_vat') * 100
            : 0;

        /* Check selected order option and return grouped collection */

        if ($this->orderBy == 'year'){
            $this->invoices = $invoices
                ->groupBy([
                    fn(Invoice $invoice) => $invoice->custom_created_at->format('Y'),
                    fn(Invoice $invoice) => $invoice->client_id,
                ])
                ->toBase()
            ;

            $this->expenses = $expensesExclVATCategory
                ->groupBy([
                    fn(Expense $expense) => $expense->invoice_date->format('Y'),
                    fn(Expense $expense) => $expense->supplier_id,
                ])
                ->toBase()
            ;

            $this->expensesVAT = $expensesVATCategory
                ->groupBy([
                    fn(Expense $expense) => $expense->invoice_date->format('Y'),
                    fn(Expense $expense) => $expense->supplier_id,
                ])
                ->toBase()
            ;

            $this->expensesTotal = $expensesTotal
                ->groupBy([
                    fn(Expense $expense) => $expense->invoice_date->format('Y'),
                    fn(Expense $expense) => $expense->supplier_id,
                ])
                ->toBase()
            ;

            $this->cashInTotal = $cashInTotal->groupBy([
                fn(Invoice $invoice) => $invoice->paid_at->format('Y'),
                fn(Invoice $invoice) => $invoice->client_id,
            ])
                ->toBase()
            ;
        }

        if ($this->orderBy == 'month'){
            $this->invoices = $invoices
                ->groupBy([
                    fn(Invoice $invoice) => $invoice->custom_created_at->format('Y/m'),
                    fn(Invoice $invoice) => $invoice->client_id,
                ])
                ->toBase()
            ;

            $this->expenses = $expensesExclVATCategory
                ->groupBy([
                    fn(Expense $expense) => $expense->invoice_date->format('Y/m'),
                    fn(Expense $expense) => $expense->supplier_id,
                ])
                ->toBase()
            ;

            $this->expensesVAT = $expensesVATCategory
                ->groupBy([
                    fn(Expense $expense) => $expense->invoice_date->format('Y/m'),
                    fn(Expense $expense) => $expense->supplier_id,
                ])
                ->toBase()
            ;

            $this->expensesTotal = $expensesTotal
                ->groupBy([
                    fn(Expense $expense) => $expense->invoice_date->format('Y/m'),
                    fn(Expense $expense) => $expense->supplier_id,
                ])
                ->toBase()
            ;

            $this->cashInTotal = $cashInTotal->groupBy([
                fn(Invoice $invoice) => $invoice->paid_at->format('Y/m'),
                fn(Invoice $invoice) => $invoice->client_id,
            ])
                ->toBase()
            ;
        }
    }

    public function getTopMostClient(Collection $invoices)
    {
        /** @var Collection<Invoice> $invoices */
        $invoices = clone $invoices;

        $currentTopClient = null;
        $currentTopClientSum = 0;

        /** @var Collection<Invoice> $invoicesByClientId */
        foreach ($invoices->groupBy('client_id') as $clientId => $invoicesByClientId){
            $sum = $invoicesByClientId->sum('amount');

            if ($sum > $currentTopClientSum){
                $currentTopClient = $clientId;
                $currentTopClientSum = $sum;
            }
        }

        $this->topMostClient = Client::find($currentTopClient);
        $this->topMostClientAmount = $currentTopClientSum;
    }

    public function getTopMostSupplier(Collection $expenses)
    {
        /** @var Collection<Expense> $expenses */
        $expenses = clone $expenses;

        $expenses = $expenses->filter(fn(Expense $expense) => ! $expense->supplier->is_general);

        $currentTopSupplier = null;
        $currentTopSupplierSum = 0;

        /** @var Collection<Expense> $expensesBySupplierId */
        foreach ($expenses->groupBy('supplier_id') as $supplierId => $expensesBySupplierId) {
            $sum = $expensesBySupplierId->sum('amount_excluding_vat');

            if ($sum > $currentTopSupplierSum){
                $currentTopSupplier = $supplierId;
                $currentTopSupplierSum = $sum;
            }
        }

        $this->topMostSupplier = Supplier::find($currentTopSupplier);
        $this->topMostSupplierAmount = $currentTopSupplierSum;
    }

    public function changeOrderBy(string $orderBy)
    {
        $this->orderBy = $orderBy;
        $this->report();
    }

    #[On('shareDates')]
    public function shareDates(?string $from, ?string $till)
    {
        $this->from = $from ? Carbon::parse($from) : now()->subMillennia(2);
        $this->till = $till ? Carbon::parse($till) : now()->addMillennia(2);

        $this->report();
    }

    public function render(): View
    {
        return view('livewire.ampp.billing-reports.overview');
    }
}
