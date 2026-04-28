<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Actions\Clearfacts\DownloadFromClearfactsAction;
use App\Actions\Clearfacts\FetchAdministrationsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Throwable;

class FetchClearfactsAdministrationsController extends Controller
{
    public function __invoke(
        FetchAdministrationsAction $fetchAdministrationsAction
    ){
        try {
            $response = $fetchAdministrationsAction->execute();

            dd($response->body(), $response->json());

            if ($response->failed() || $response->collect()->has('errors')){
                session()->flash('error', __('Something went wrong, please contact our support.'));
                return redirect()->back();
            }

            $data = $response->collect();

            $administrations = data_get($data, 'data.administrations', []);

            dd($administrations);

            return view('ampp.invoices.clearfacts.administrations', [
                'administrations' => collect($administrations),
            ]);
        } catch (Throwable $e) {
            dd($e);
            Log::error('Error fetching Clearfacts administrations: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            session()->flash('error', __('An unexpected error occurred, please contact our support.'));
            return redirect()->back();
        }
    }
}
