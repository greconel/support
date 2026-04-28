<?php

namespace App\Http\Controllers\Ampp\GdprMessages;

use App\Http\Controllers\Controller;
use App\Models\GdprMessage;

class DestroyGdprMessageController extends Controller
{
    public function __invoke(GdprMessage $gdprMessage)
    {
        $this->authorize('delete', $gdprMessage);

        $gdprMessage->delete();

        session()->flash('success', __('GDPR message deleted'));

        return redirect()->action(IndexGdprMessageController::class);
    }
}
