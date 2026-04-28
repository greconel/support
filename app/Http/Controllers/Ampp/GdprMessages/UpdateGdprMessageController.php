<?php

namespace App\Http\Controllers\Ampp\GdprMessages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\GdprMessages\UpdateGdprMessageRequest;
use App\Models\GdprMessage;
use Illuminate\Http\Request;

class UpdateGdprMessageController extends Controller
{
    public function __invoke(UpdateGdprMessageRequest $request, GdprMessage $gdprMessage)
    {
        $this->authorize('update', $gdprMessage);

        $gdprMessage->update($request->all());

        return redirect()
            ->action(IndexGdprMessageController::class)
            ->with('success', __('Successfully edited GDPR message'))
        ;
    }
}
