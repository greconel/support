<?php

namespace App\Http\Controllers\Ampp;

use App\Actions\Invoices\CalculateOutstandingAmountAction;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientContact;
use App\Models\Connection;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\TimeRegistration;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view ampp zone|view admin zone');
    }

    public function __invoke(CalculateOutstandingAmountAction $calculateOutstandingAmountAction)
    {
        // client chart
        $clients = Client::whereYear('created_at', now()->year)
            ->get()
            ->groupBy(function ($q) {
                return Carbon::parse($q->created_at)->format('m');
            });

        // invoices chart
        $invoices = Invoice::whereYear('custom_created_at', now()->year)
            ->get()
            ->groupBy(function ($q) {
                return Carbon::parse($q->custom_created_at)->format('m');
            });

        return view('ampp.dashboard', [
            'totalClients' => Client::count(),
            'totalContacts' => ClientContact::count(),
            'totalProjects' => Project::count(),
            'timeRegistrationsToday' => TimeRegistration::whereDate('start', now())->count(),
            'totalInvoices' => Invoice::count(),
            'invoicesThisYear' => Invoice::whereYear('custom_created_at', now()->format('Y'))->count(),
            'totalConnection' => Connection::count(),
            'connectionsInUse' => Connection::where('in_use', true)->count(),
            'clients' => $clients,
            'invoices' => $invoices,
            'outstandingInvoicesNotExpiredInclVat' => $calculateOutstandingAmountAction->execute(now()),
            'outstandingInvoicesNotExpiredExclVat' => $calculateOutstandingAmountAction->execute(now(), vat: false),
            'outstandingInvoicesExpiredForMax30DaysWithVat' => $calculateOutstandingAmountAction->execute(now()->subDays(30), now()),
            'outstandingInvoicesExpiredForMax30DaysExclVat' => $calculateOutstandingAmountAction->execute(now()->subDays(30), now(), false),
            'outstandingInvoicesExpiredLongerThen30DaysInclVat' => $calculateOutstandingAmountAction->execute(expiresTill: now()->subDays(30)),
            'outstandingInvoicesExpiredLongerThen30DaysExclVat' => $calculateOutstandingAmountAction->execute(expiresTill: now()->subDays(30), vat: false),
        ]);
    }
}
