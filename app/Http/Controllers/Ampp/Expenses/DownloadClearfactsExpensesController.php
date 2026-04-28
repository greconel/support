<?php

namespace App\Http\Controllers\Ampp\Expenses;
use App\Actions\Clearfacts\DownloadFromClearfactsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DownloadClearfactsExpensesController extends Controller
{
    public function __invoke(
        DownloadFromClearfactsAction $downloadFromClearfactsAction,
        Request $request
    ){
        try {
            $afterCursor = $request->query('after');

            $response = $downloadFromClearfactsAction->execute(
                config('services.clearfacts.vat'),
                $afterCursor
            );

            dd($response->body(), $response->json());

            if ($response->failed() || $response->collect()->has('errors')){
                session()->flash('error', __('Something went wrong, please contact our support.'));
                return redirect()->back();
            }

            $data = $response->collect();

            $purchaseDocuments = data_get($data, 'data.purchaseDocuments', []);

            dd($purchaseDocuments);

            return view('ampp.expenses.clearfacts.downloaded_expenses', [
                'purchaseDocuments' => collect($purchaseDocuments),
            ]);
        } catch (\Throwable $e) {
            dd($e);
            Log::error('Error downloading Clearfacts expenses: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            session()->flash('error', __('An unexpected error occurred, please contact our support.'));
            return redirect()->back();
        }
    }
}
