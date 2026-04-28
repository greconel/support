<x-layouts.ampp :title="__('Expenses')">
    <div class="page-header">
        <x-ui.page-title>{{ __('All expenses') }}</x-ui.page-title>

        <x-ui.split-button>
            <x-slot name="button">
                <a href="{{ action(\App\Http\Controllers\Ampp\Expenses\CreateExpenseController::class) }}" class="btn btn-primary">
                    {{ __('Create new expense') }}
                </a>
            </x-slot>

            <x-slot name="dropdown">
                <li>
                    <a href="{{ action(\App\Http\Controllers\Ampp\Expenses\IndexClearfactsBulkExpenseController::class) }}" class="dropdown-item">
                        {{ __('Clearfacts bulk upload') }}
                    </a>
                </li>

                <li>
                    <a href="{{ action(\App\Http\Controllers\Ampp\Expenses\ExportExpensesController::class) }}" class="dropdown-item">
                        {{ __('Export expenses') }}
                    </a>
                </li>
            </x-slot>
        </x-ui.split-button>
    </div>

    <div class="card card-body">
        <div class="dataTable-header">
            <x-table.filter table-id="expense-table" :placeholder="__('Search expenses...')" />

            <div class="dataTable-filters">
                @foreach(\App\Enums\ExpenseStatus::cases() as $status)
                    <x-table.filters-option state="status" value="{{ $status->value }}" :count="\App\Models\Expense::where('status', '=', $status)->count()">
                        {{ $status->label()  }}
                    </x-table.filters-option>
                @endforeach
            </div>
        </div>

        {{ $dataTable->table() }}
    </div>

    <x-push name="scripts">
        {{ $dataTable->scripts() }}

        <x-scripts.clickable-table-row id="expense-table" :redirect="action(\App\Http\Controllers\Ampp\Expenses\ShowExpenseController::class, ':id')" />
    </x-push>
</x-layouts.ampp>
