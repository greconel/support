<?php

namespace App\Jobs\DataImport;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportClientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $clients = DB::connection('old_ampp')->table('clients')->get();

        foreach ($clients as $client){
            Client::unguard();

            $newClient = Client::create([
                'id' => $client->id,
                'first_name' => $client->firstname ?? '--TODO--',
                'last_name' => $client->name,
                'company' => $client->company,
                'email' => $client->email,
                'phone' => $client->phone,
                'vat' => $client->btw,
                'street' => $client->street,
                'number' => $client->bus ? "{$client->number} bus {$client->bus}" : $client->number,
                'postal' => $client->postalcode,
                'city' => $client->place,
                'country' => $this->getCountry($client->country),
                'country_code' => $this->getCountryCode($client->country),
                'lat' => $client->lat,
                'lng' => $client->lng,
                'created_at' => $client->created_at,
                'updated_at' => $client->updated_at,
                'deleted_at' => $client->archive ? now() : null
            ]);

            Client::reguard();

            if ($client->email2){
                $newClient->contacts()->create([
                    'first_name' => $newClient->first_name,
                    'last_name' => $newClient->last_name,
                    'email' => $client->email2,
                    'tags' => ['Created from import']
                ]);
            }

            if ($client->phone2){
                $newClient->contacts()->create([
                    'first_name' => $newClient->first_name,
                    'last_name' => $newClient->last_name,
                    'phone' => $client->phone2,
                    'tags' => ['Created from import']
                ]);
            }
        }
    }

    private function getCountry(string $country = null): string
    {
        if ($country == 'België'){
            return 'Belgium';
        }

        if ($country == 'Nederland'){
            return 'Netherlands';
        }

        return 'Belgium';
    }

    private function getCountryCode(string $country = null): string
    {
        if ($country == 'België'){
            return 'BE';
        }

        if ($country == 'Nederland'){
            return 'NL';
        }

        return 'BE';
    }
}
