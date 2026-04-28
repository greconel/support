<x-layouts.ampp :title="__('Edit recurring invoice lines')" :breadcrumbs="Breadcrumbs::render('editRecurringInvoiceLines', $recurringInvoice)">
    <div class="container-fluid">
        <livewire:ampp.recurring-invoices.lines :recurring-invoice="$recurringInvoice" :session-key="'recurring_invoice_' . $recurringInvoice->id . '_lines'" />
    </div>
</x-layouts.ampp>
