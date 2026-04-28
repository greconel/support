<?php

namespace App\View\Components\Ampp\BillingReports;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class InvoiceOverview extends Component
{
    public function __construct(
        public Collection $invoicesGrouped,
        public string $orderBy
    ){}

    public function getClient(int $id): Client
    {
        return Client::withTrashed()->find($id);
    }

    public function calculateTotal(Collection $invoicesGroupedByClientId)
    {
        $total = 0;

        foreach ($invoicesGroupedByClientId as $invoices){
            $total += $invoices->sum('amount');
        }

        return $total;
    }

    public function calculateTotalWithVat(Collection $invoicesGroupedByClientId)
    {
        $total = 0;

        foreach ($invoicesGroupedByClientId as $invoices){
            $total += $invoices->sum('amount_with_vat');
        }

        return $total;
    }

    /**
     * @return mixed
     * @var Collection<Invoice> $invoices
     */
    public function calculateTotalForClient(Collection $invoices)
    {
        return $invoices->sum('amount');
    }

    /**
     * @return mixed
     * @var Collection<Invoice> $invoices
     */
    public function calculateTotalForClientWithVat(Collection $invoices)
    {
        return $invoices->sum('amount_with_vat');
    }

    public function render(): View
    {
        return view('components.ampp.billing-reports.invoice-overview');
    }
}
