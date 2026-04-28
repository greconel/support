<?php

namespace App\Http\Controllers\Admin\Artisan;

use App\Http\Controllers\Controller;

class IndexArtisanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:artisan');
    }

    public function __invoke()
    {
        return view('admin.artisan.index');
    }
}
