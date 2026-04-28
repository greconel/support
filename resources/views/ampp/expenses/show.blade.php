<x-layouts.ampp :title="__('Expense :name', ['name' => $expense->name])" :breadcrumbs="Breadcrumbs::render('showExpense', $expense)">
    <div class="container">
        <x-ui.page-title>{{ __('Expense :name', ['name' => $expense->name]) }}</x-ui.page-title>

        <div class="row justify-content-center">
            <div class="col-lg-5 mb-5 mb-lg-0 d-flex flex-column gap-4">
                <livewire:ampp.expenses.details :expense="$expense" />

                <div class="card mt-4">
                    <div class="card-header">
                        {{ __('Comment') }}
                    </div>

                    <div class="card-body">
                        <x-ui.quill-display :content="$expense->comment" />
                    </div>
                </div>

                {{-- CLearfacts notes --}}
                <livewire:ampp.expenses.clearfacts-comment :expense="$expense" />
            </div>

            <div class="col-lg-7">
                @if($expense->getFirstMedia())
                    <div
                        x-data
                        x-init="PDFObject.embed('{{ action(\App\Http\Controllers\Media\ShowMediaController::class, $expense->getFirstMedia()) }}', $refs.pdfViewer)"
                        x-ref="pdfViewer"
                        style="height: 85vh">
                    </div>
                @else
                    <p class="text-center text-muted">{{ __('No PDF available..') }}</p>
                @endif
            </div>
        </div>
    </div>

    <x-push name="modals">
        <livewire:ampp.expenses.clearfacts-comment-modal :expense="$expense" />
    </x-push>
</x-layouts.ampp>
