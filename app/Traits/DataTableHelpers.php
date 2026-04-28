<?php

namespace App\Traits;

trait DataTableHelpers
{
    public function getAllColumnNames(): array
    {
        return array_merge(
            collect($this->getColumns())->pluck('name')->toArray(),
            collect($this->getColumns())->pluck('data')->toArray()
        );
    }
}
