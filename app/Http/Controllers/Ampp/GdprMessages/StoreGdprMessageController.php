<?php

namespace App\Http\Controllers\Ampp\GdprMessages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\GdprMessages\StoreGdprMessageRequest;
use App\Models\GdprMessage;
use Illuminate\Http\Request;

class StoreGdprMessageController extends Controller
{
    public function __invoke(StoreGdprMessageRequest $request)
    {
        $this->authorize('create', GdprMessage::class);

        GdprMessage::create($request->all());

        return redirect()
            ->action(IndexGdprMessageController::class)
            ->with('success', __('Successfully created GDPR message'))
        ;
    }
}
