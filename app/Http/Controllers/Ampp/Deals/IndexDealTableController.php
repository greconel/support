<?php

namespace App\Http\Controllers\Ampp\Deals;

use App\DataTables\Ampp\DealDataTable;

class IndexDealTableController
{
    public function __invoke(DealDataTable $dataTable)
    {
        return $dataTable->render('ampp.deals.table');
    }
}
