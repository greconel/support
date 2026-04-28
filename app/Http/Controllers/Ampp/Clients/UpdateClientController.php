<?php

namespace App\Http\Controllers\Ampp\Clients;

use App\Classes\CountriesHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Clients\UpdateClientRequest;
use App\Models\Client;
use Spatie\Geocoder\Facades\Geocoder;

class UpdateClientController extends Controller
{
    public function __invoke(UpdateClientRequest $request, $id)
    {
        $client = Client::withTrashed()->findOrFail($id);

        $this->authorize('update', $client);

        $input = $request->except('get_coordinates');
        $input['peppol_only'] = $request->has('peppol_only');

        if ($request->has('get_coordinates')) {
            try {
                $geoResults = Geocoder::getCoordinatesForAddress(
                    <<<TEXT
                    {$request->input('street')} {$request->input('number')},
                    {$request->input('postal')} {$request->input('city')},
                    {$request->input('country')}
                    TEXT
                );

                throw_if(
                    $geoResults['accuracy'] == 'result_not_found',
                    new \Exception(__('Address not found'))
                );

                $input['lat'] = $geoResults['lat'];
                $input['lng'] = $geoResults['lng'];
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('address_error', $e->getMessage())
                ;
            }
        }

        if ($request->has('country')) {
            $countriesHelper = new CountriesHelper();
            $input['country_code'] = $countriesHelper->findCca2($request->input('country'));
        }

        $client->update($input);

        session()->flash('success', __('Updated client'));

        return redirect()->action(ShowClientController::class, $client);
    }
}
