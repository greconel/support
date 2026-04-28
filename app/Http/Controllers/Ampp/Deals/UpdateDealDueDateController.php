<?php

namespace App\Http\Controllers\Ampp\Deals;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Deals\UpdateDealDueDateRequest;
use App\Models\Deal;

class UpdateDealDueDateController extends Controller
{
    public function __invoke(UpdateDealDueDateRequest $request, Deal $deal)
    {
        $this->authorize('update', $deal);

        $deal->update(['due_date' => $request->input('due_date')]);

        session()->flash('success', __('Due date updated, a new reminder has been set'));

        return redirect()->action(ShowDealController::class, $deal);
    }
}
