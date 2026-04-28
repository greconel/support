<x-layouts.ampp :title="__('Edit quotation contents')" :breadcrumbs="Breadcrumbs::render('editQuotationLines', $quotation)">
    <div class="container-fluid">
        <livewire:ampp.quotations.lines :quotation="$quotation" :session-key="'quotation_' . $quotation->id . '_lines'" />
    </div>
</x-layouts.ampp>
