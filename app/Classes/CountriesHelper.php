<?php


namespace App\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use League\ISO3166\ISO3166;

class CountriesHelper
{
    public ISO3166 $countries;

    public function __construct()
    {
        $this->countries = new ISO3166();
    }

    public function getLocaleList(): Collection
    {
        $countries = collect($this->countries->all());

        return $countries->pluck('name', 'name');
    }

    public function findCca2(string $commonName): ?string
    {
        try {
            $country = $this->countries->name($commonName);
            return $country['alpha2'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
