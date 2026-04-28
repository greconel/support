<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditModal extends Component
{
    use AuthorizesRequests;

    public Invoice $invoice;
    public array $clients = [];
    public array $invoiceCategories = [];
    public string $expirationDate = '';
    public string $customCreatedAt = '';

    protected $rules = [
        'invoice.client_id' => ['required', 'int'],
        'invoice.number' => ['required', 'int'],
        'invoice.po_number' => ['nullable', 'string', 'max:255'],
        'invoice.invoice_category_id' => ['nullable', 'integer'],
        'expirationDate' => ['required', 'date'],
        'customCreatedAt' => ['required', 'date'],
    ];

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->clients = Client::all()->pluck('full_name_with_company', 'id')->toArray();
        $this->invoiceCategories = InvoiceCategory::all()->pluck('name', 'id')->toArray();

        $this->expirationDate = $invoice->expiration_date->format('Y-m-d');
        $this->customCreatedAt = $invoice->custom_created_at->format('Y-m-d');
    }

    public function edit()
    {
        $this->authorize('update', $this->invoice);

        $this->validate();

        $this->validate([
            'invoice.number' => [
                Rule::unique('invoices', 'number')->where(function ($q){
                    return $q->whereYear('custom_created_at', $this->invoice->custom_created_at);
                })->ignoreModel($this->invoice)
            ]
        ]);

        $this->invoice->expiration_date = $this->expirationDate;
        $this->invoice->custom_created_at = $this->customCreatedAt;
        $this->invoice->save();

        $this->dispatch('close')->self();
        $this->dispatch('refreshInvoice')->to('ampp.invoices.show.details');

    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.edit-modal');
    }
}
