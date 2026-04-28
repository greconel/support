<x-layouts.ampp :title="__('Quotation :name', ['name' => $quotation->custom_name])" :breadcrumbs="Breadcrumbs::render('showQuotation', $quotation)">
    <div class="container">
        <x-ui.page-title>{{ __('Quotation :name', ['name' => $quotation->custom_name]) }}</x-ui.page-title>

        <div class="row justify-content-center">
            <div class="col-lg-5">
                {{-- Details --}}
                <livewire:ampp.quotations.show.details :quotation="$quotation" />

                {{-- Notes --}}
                <livewire:ampp.quotations.show.notes :quotation="$quotation" />

                {{-- Files --}}
                <livewire:ampp.quotations.show.files :quotation="$quotation" />
            </div>

            <div class="col-lg-7">
                {{-- Quotation lines --}}
                <x-ampp.quotations.show.quotation-overview :quotation="$quotation" />

                {{-- Emails --}}
                <div class="card">
                    <div class="card-header">
                        {{ __('E-mails') }}
                    </div>

                    <div class="card-body">
                        <livewire:ampp.emails.list-for-model :email-model="$quotation" create-modal="quotationEmailModal" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-push name="modals">
        <livewire:ampp.quotations.show.edit-modal :quotation="$quotation" />
        <livewire:ampp.quotations.show.note-modal :quotation="$quotation" />
        <livewire:ampp.quotations.show.pdf-comment-modal :quotation="$quotation" />
        <livewire:ampp.quotations.show.pdf-preview :quotation="$quotation" />
        <livewire:ampp.quotations.show.email-modal :quotation="$quotation" />
        <livewire:ampp.emails.preview-modal :email-model="$quotation" />
        <livewire:ampp.media.preview-modal />

        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this quotation? This action can not be reverted!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\Quotations\DestroyQuotationController::class, $quotation)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
