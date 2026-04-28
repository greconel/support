<?php

namespace App\Http\Livewire\Ampp\Deals;

use App\Models\Deal;
use App\Models\DealColumn;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Board extends Component
{
    public string $columnName = '';

    #[On('refreshBoard')]
    public function refreshBoard()
    {
        // This triggers a component refresh
    }

    public function updateColumnOrder($newOrders)
    {
        foreach ($newOrders as $newOrder){
            $column = DealColumn::find($newOrder['value']);
            $column->update(['order' => $newOrder['order']]);
        }
    }

    public function updateDealOrder($newOrders)
    {
        foreach ($newOrders as $newOrder){
            $column = DealColumn::find($newOrder['value']);

            foreach ($newOrder['items'] as $item){
                $deal = Deal::find($item['value']);

                $deal->update([
                    'deal_column_id' => $column->id,
                    'order' => $item['order']
                ]);
            }
        }
    }

    public function addColumn()
    {
        $this->validate([
            'columnName' => ['required', 'string', 'max:255']
        ]);

        DealColumn::create([
            'name' => $this->columnName,
            'order' => DealColumn::max('order') + 1
        ]);

        $this->reset('columnName');
    }

    public function editColumn(DealColumn $dealColumn)
    {
        $this->validate([
            'columnName' => ['required', 'string', 'max:255']
        ]);

        $dealColumn->update(['name' => $this->columnName,]);

        $this->reset('columnName');
    }

    public function deleteColumn(DealColumn $dealBoardColumn)
    {
        $dealBoardColumn->delete();

        $this->dispatch('closeColumnDeleteModal')->self();
    }

    public function getPipelineRevenueForColumn(DealColumn $dealColumn): string
    {
        $pipelineRevenue = 0;

        foreach ($dealColumn->deals as $deal) {
            if (! $deal->expected_revenue){
                continue;
            }

            if (! $deal->chance_of_success){
                continue;
            }

            $pipelineRevenue += $deal->expected_revenue * ($deal->chance_of_success / 100);
        }

        return number_format($pipelineRevenue, 2, ',', ' ');
    }

    public function sortBy(DealColumn $dealColumn, string $columnToSort, string $direction)
    {
        $direction = $direction === 'desc';

        $dealColumn
            ->deals
            ->sortBy($columnToSort, SORT_REGULAR, $direction)
            ->values()
            ->each(fn(Deal $deal, $order) => $deal->update(['order' => (int) $order + 1]))
        ;
    }

    public function render(): View
    {
        return view('livewire.ampp.deals.board', [
            'columns' => DealColumn::all()
        ]);
    }
}
