<?php

namespace App\Jobs\DataImport;

use App\Models\Supplier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportSuppliersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $suppliers = DB::connection('old_ampp')->table('suppliers')->get();

        foreach ($suppliers as $supplier) {
            Supplier::unguard();

            Supplier::create([
                'id' => $supplier->id,
                'first_name' => $supplier->first_name,
                'last_name' => $supplier->last_name,
                'company' => $supplier->company,
                'vat' => $supplier->btw,
                'iban' => $supplier->iban,
                'email' => $supplier->email,
                'phone' => $supplier->phone,
                'street' => $supplier->street,
                'number' => $supplier->number,
                'postal' => $supplier->postal,
                'city' => $supplier->place,
                'country' => $this->getCountry($supplier->country),
                'country_code' => $this->getCountryCode($supplier->country),
                'created_at' => $supplier->created_at,
                'updated_at' => $supplier->updated_at
            ]);

            Supplier::reguard();
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

        if ($country == 'Germany'){
            return 'Germany';
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

        if ($country == 'Germany'){
            return 'DE';
        }

        return 'BE';
    }
}
