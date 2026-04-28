<?php

namespace App\Http\Controllers\Api\V1\Clients;

use App\Classes\CountriesHelper;
use App\Http\Requests\Api\V1\Clients\ClientFormRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;

class UpdateClientController
{
    /**
     * Update an existing client
     *
     * @group Clients
     * @header Accept application/json
     *
     */
    public function __invoke(ClientFormRequest $request, Client $client)
    {
        $input = $request->except('get_coordinates');

        if ($request->has('country')) {
            $countriesHelper = new CountriesHelper();
            $input['country_code'] = $countriesHelper->findCca2($request->input('country'));
        }

        $client->update($input);

        return new ClientResource($client->refresh());
    }
}
