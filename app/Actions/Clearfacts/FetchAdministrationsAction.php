<?php

namespace App\Actions\Clearfacts;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchAdministrationsAction
{
    public function execute(): Response
    {
        $graphQlQuery = <<<GRAPHQL
        query Administrations {
            administrations {
                companyNumber
                name
            }
        }
        GRAPHQL;

        return Http::withToken(config('services.clearfacts.token'))
            ->post(config('services.clearfacts.url'), [
                'query' => $graphQlQuery,
            ]);
    }
}
