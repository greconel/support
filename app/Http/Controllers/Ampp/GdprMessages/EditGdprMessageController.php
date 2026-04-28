<?php

namespace App\Http\Controllers\Ampp\GdprMessages;

use App\Http\Controllers\Controller;
use App\Models\GdprMessage;

class EditGdprMessageController extends Controller
{
    public function __invoke(GdprMessage $gdprMessage)
    {
        $this->authorize('update', $gdprMessage);

        return view('ampp.gdprMessages.edit', [
            'message' => $gdprMessage
        ]);
    }
}
