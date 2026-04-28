<?php

namespace App\Http\Controllers\Api\V1\Clients;

use App\Classes\CountriesHelper;
use App\Http\Requests\Api\V1\Clients\ClientFormRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;

class StoreClientController
{
    /**
     * Create a new client
     *
     * @group Clients
     * @header Accept application/json
     *
     * @bodyParam type string required The type of client, this can be client, lead or bms
     *
     */
    public function __invoke(ClientFormRequest $request)
    {
        $input = $request->except('get_coordinates');

        if ($request->has('country')) {
            $countriesHelper = new CountriesHelper();
            $input['country_code'] = $countriesHelper->findCca2($request->input('country'));
        }

        $client = Client::create($input);

        return new ClientResource($client);
    }
}
