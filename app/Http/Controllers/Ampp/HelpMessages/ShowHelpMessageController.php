<?php

namespace App\Http\Controllers\Ampp\HelpMessages;

use App\Http\Controllers\Controller;
use App\Models\HelpMessage;

class ShowHelpMessageController extends Controller
{
    public function __invoke(HelpMessage $helpMessage)
    {
        $this->authorize('view', $helpMessage);

        return view('ampp.helpMessages.show',[
            'helpMessage' => $helpMessage
        ]);
    }
}
