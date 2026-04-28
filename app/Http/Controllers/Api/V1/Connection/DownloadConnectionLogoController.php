<?php

namespace App\Http\Controllers\Api\V1\Connection;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use Illuminate\Http\Request;

class DownloadConnectionLogoController extends Controller
{
    public function __invoke(Request $request, Connection $connection)
    {
        if (! $request->hasValidSignature()){
            abort(401);
        }

        $logo = $connection->getFirstMedia('logo');

        //dd($logo->getPath(), $logo);

        return response()->download($logo->getPath(), $logo->file_name);
    }
}