<?php

namespace App\View\Components\Ampp\BillingReports;

use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ChartOverview extends Component
{
    public array $ranges = [];

    public function __construct(
        public Collection $invoicesGrouped,
        public Collection $expensesGrouped,
        public Collection $expensesVatGrouped,
        public Collection $expensesTotal,
        public Collection $cashInTotal
    ){
        $this->invoicesGrouped->keys()->each(fn ($range) => $this->ranges[] = $range);
        $this->expensesGrouped->keys()->each(fn ($range) => $this->ranges[] = $range);
        $this->expensesVatGrouped->keys()->each(fn ($range) => $this->ranges[] = $range);
        $this->expensesTotal->keys()->each(fn ($range) => $this->ranges[] = $range);
        $this->cashInTotal->keys()->each(fn ($range) => $this->ranges[] = $range);

        $this->ranges = collect($this->ranges)->unique()->toArray();
    }

    public function labels(): array
    {
        return [
            __('January'),
            __('February'),
            __('March'),
            __('April'),
            __('May'),
            __('June'),
            __('July'),
            __('August'),
            __('September'),
            __('October'),
            __('November'),
            __('December')
        ];
    }

    public function invoiceData(string $range)
    {
        /** @var Collection<Invoice> $invoices */
        $invoices = $this->invoicesGrouped->has($range)
            ? clone $this->invoicesGrouped[$range]->flatten()
            : collect();

        $totalsPerMonth = [];

        for ($i = 1; $i <= 12; $i++){
            $invoicesForMonth = clone $invoices;

            $invoicesForMonth = $invoicesForMonth->filter(function (Invoice $invoice) use ($i){
                return $invoice->custom_created_at->month == $i;
            });

            $totalsPerMonth[] = $invoicesForMonth->sum('amount');
        }

        return $totalsPerMonth;
    }

    public function invoiceDataIncl(string $range)
    {
        /** @var Collection<Invoice> $invoices */
        $invoices = $this->invoicesGrouped->has($range)
            ? clone $this->invoicesGrouped[$range]->flatten()
            : collect();

        $totalsPerMonth = [];

        for ($i = 1; $i <= 12; $i++){
            $invoicesForMonth = clone $invoices;

            $invoicesForMonth = $invoicesForMonth->filter(function (Invoice $invoice) use ($i){
                return $invoice->custom_created_at->month == $i;
            });

            $totalsPerMonth[] = $invoicesForMonth->sum('amount_with_vat');
        }

        return $totalsPerMonth;
    }

    public function cashInTotalData(string $range)
    {
        /** @var Collection<Invoice> $invoices */
        $invoices = $this->cashInTotal->has($range)
            ? clone $this->cashInTotal[$range]->flatten()
            : collect();

        $totalsPerMonth = [];

        for ($i = 1; $i <= 12; $i++){
            $invoicesForMonth = clone $invoices;

            $invoicesForMonth = $invoicesForMonth->filter(function (Invoice $invoice) use ($i){
                return $invoice->paid_at->month == $i;
            });

            $totalsPerMonth[] = $invoicesForMonth->sum('amount_with_vat');
        }

        return $totalsPerMonth;
    }

    public function expenseData(string $range)
    {
        /** @var Collection<Expense> $expenses */
        $expenses = $this->expensesGrouped->has($range)
            ? clone $this->expensesGrouped[$range]->flatten()
            : collect();

        $totalsPerMonth = [];

        for ($i = 1; $i <= 12; $i++){
            $expensesForMonth = clone $expenses;

            $expensesForMonth = $expensesForMonth->filter(function (Expense $expense) use ($i){
                return $expense->invoice_date->month == $i;
            });

            $totalsPerMonth[] = $expensesForMonth->sum('amount_excluding_vat');
        }

        return $totalsPerMonth;
    }

    public function expenseDataInclVat(string $range)
    {
        /** @var Collection<Expense> $expenses */
        $expenses = $this->expensesGrouped->has($range)
            ? clone $this->expensesGrouped[$range]->flatten()
            : collect();

        $totalsPerMonth = [];

        for ($i = 1; $i <= 12; $i++){
            $expensesForMonth = clone $expenses;

            $expensesForMonth = $expensesForMonth->filter(function (Expense $expense) use ($i){
                return $expense->invoice_date->month == $i;
            });

            $totalsPerMonth[] = $expensesForMonth->sum('amount_including_vat');
        }

        return $totalsPerMonth;
    }

    public function expenseDataTotal(string $range)
    {
        /** @var Collection<Expense> $expenses */
        $expenses = $this->expensesTotal->has($range)
            ? clone $this->expensesTotal[$range]->flatten()
            : collect();

        $totalsPerMonth = [];

        for ($i = 1; $i <= 12; $i++){
            $expensesForMonth = clone $expenses;

            $expensesForMonth = $expensesForMonth->filter(function (Expense $expense) use ($i){
                return $expense->invoice_date->month == $i;
            });

            $totalsPerMonth[] = $expensesForMonth->sum('amount_including_vat');
        }

        return $totalsPerMonth;
    }


    public function expenseDataVAT(string $range)
    {
        /** @var Collection<Expense> $expenses */
        $expenses = $this->expensesVatGrouped->has($range)
            ? clone $this->expensesVatGrouped[$range]->flatten()
            : collect();

        $totalsPerMonth = [];

        for ($i = 1; $i <= 12; $i++){
            $expensesForMonth = clone $expenses;

            $expensesForMonth = $expensesForMonth->filter(function (Expense $expense) use ($i){
                return $expense->invoice_date->month == $i;
            });

            $totalsPerMonth[] = $expensesForMonth->sum('amount_excluding_vat');
        }

        return $totalsPerMonth;
    }

    public function render(): View
    {
        return view('components.ampp.billing-reports.chart-overview');
    }
}
