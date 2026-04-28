<?php

namespace App\Http\Controllers\Ampp\GdprMessages;

use App\Http\Controllers\Controller;
use App\Models\GdprMessage;

class CreateGdprMessageController extends Controller
{
    public function __invoke()
    {
        $this->authorize('create', GdprMessage::class);

        return view('ampp.gdprMessages.create');
    }
}
