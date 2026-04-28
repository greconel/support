<?php

namespace App\Http\Controllers\Admin\Connections;

use App\Http\Controllers\Ampp\Connections\IndexConnectionController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Connections\StoreConnectionRequest;
use App\Models\Connection;
use Intervention\Image\Laravel\Facades\Image;

class StoreConnectionController extends Controller
{
    public function __invoke(StoreConnectionRequest $request)
    {
        $this->authorize('create', Connection::class);

        $connection = Connection::create([
            'name' => $request->input('name'),
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'in_use' => 0
        ]);

        if ($request->hasFile('icon')) {
            $img = Image::read($request->file('icon'));

            $img->scale(width: 100);

            $img->resizeCanvas(100, 100);

            $img->save($request->file('icon')->getPathname());

            $connection->addMediaFromRequest('icon')
                ->toMediaCollection('logo');
        }

        return redirect()->action(IndexConnectionController::class);
    }
}