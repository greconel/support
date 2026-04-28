<?php

namespace Database\Seeders;

use App\Models\Connection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\URL;

class ConnectionSeeder extends Seeder
{
    public function run()
    {
        $availableConnections = [
            ['name' => 'API/REST', 'logo' => '/images/logos/connections/api-rest.png', 'short_description' => 'Bidirectional connection'],
            ['name' => 'API/SOAP', 'logo' => '/images/logos/connections/api-soap.png', 'short_description' => 'Bidirectional connection'],
            ['name' => 'Custom C#', 'logo' => '/images/logos/connections/c-sharp.png', 'short_description' => 'Our custom made C#'],
            ['name' => 'FTP - files (CSV, XML, TXT, ...)', 'logo' => '/images/logos/connections/ftp.png', 'short_description' => 'Bidirectional connection'],
            ['name' => 'Adsolut', 'logo' => '/images/logos/connections/adsolut.png', 'short_description' => 'Accountancy'],
            ['name' => 'Adyen', 'logo' => '/images/logos/connections/adyen.png', 'short_description' => 'Payments'],
            ['name' => 'AFAS', 'logo' => '/images/logos/connections/afas.png', 'short_description' => 'ERP'],
            ['name' => 'Algolia', 'logo' => '/images/logos/connections/algolia.png', 'short_description' => 'Search engine'],
            ['name' => 'Al-wegen', 'logo' => '/images/logos/connections/al-wegen.png', 'short_description' => 'Weighbridge'],
            ['name' => 'BPost', 'logo' => '/images/logos/connections/b-post.png', 'short_description' => 'Shipping'],
            ['name' => 'Briljant', 'logo' => '/images/logos/connections/briljant.png', 'short_description' => 'Accountancy'],
            ['name' => 'CCV Pay', 'logo' => '/images/logos/connections/ccv.png', 'short_description' => 'Payments'],
            ['name' => 'Clearfacts', 'logo' => '/images/logos/connections/clearfacts.png', 'short_description' => 'External accountancy'],
            ['name' => 'Cloudflare', 'logo' => '/images/logos/connections/cloudflare.png', 'short_description' => 'Global networking'],
            ['name' => 'Eenvoudigfactureren.be', 'logo' => '/images/logos/connections/eenvoudig-factureren.png', 'short_description' => 'Billing'],
            ['name' => 'Euroxxi', 'logo' => '/images/logos/connections/arcen.png', 'short_description' => 'Concrete plants control'],
            ['name' => 'Exact Online', 'logo' => '/images/logos/connections/exact-online.png', 'short_description' => 'Accountancy'],
            ['name' => 'FileMaker', 'logo' => '/images/logos/connections/file-maker.png', 'short_description' => 'Database'],
            ['name' => 'Flexmail', 'logo' => '/images/logos/connections/flexmail.png', 'short_description' => 'E-mailmarketing'],
            ['name' => 'Google Suite', 'logo' => '/images/logos/connections/google-suite.png', 'short_description' => 'Online workspace'],
            ['name' => 'IMGIX', 'logo' => '/images/logos/connections/imgix.png', 'short_description' => 'Image processing'],
            ['name' => 'Ingenico ePayments', 'logo' => '/images/logos/connections/ingenico-e-payments.png', 'short_description' => 'Payments'],
            ['name' => 'Joyn', 'logo' => '/images/logos/connections/joyn.png', 'short_description' => 'Loyalty platform'],
            ['name' => 'KBC PayPage', 'logo' => '/images/logos/connections/kbc.png', 'short_description' => 'Payments'],
            ['name' => 'Logichef', 'logo' => '/images/logos/connections/logichef.png', 'short_description' => 'Horeca software'],
            ['name' => 'Mailchimp', 'logo' => '/images/logos/connections/mailchimp.png', 'short_description' => 'Marketing, branding, emailing'],
            ['name' => 'Messagebird', 'logo' => '/images/logos/connections/messagebird.png', 'short_description' => 'Communication'],
            ['name' => 'Mollie', 'logo' => '/images/logos/connections/mollie.png', 'short_description' => 'Payments'],
            ['name' => 'MS Dynamics', 'logo' => '/images/logos/connections/ms-dynamics.png', 'short_description' => 'Microsoft ERP'],
            ['name' => 'Multisafepay', 'logo' => '/images/logos/connections/multisafepay.png', 'short_description' => 'Payments'],
            ['name' => 'MyNuma', 'logo' => '/images/logos/connections/my-numa.png', 'short_description' => 'Email marketing'],
            ['name' => 'Office 365', 'logo' => '/images/logos/connections/office.png', 'short_description' => 'Collaboration'],
            ['name' => 'Opencart', 'logo' => '/images/logos/connections/open-cart.png', 'short_description' => 'Webshop'],
            ['name' => 'Paypal', 'logo' => '/images/logos/connections/paypal.png', 'short_description' => 'Payments'],
            ['name' => 'SendCloud', 'logo' => '/images/logos/connections/send-cloud.png', 'short_description' => 'shipping'],
            ['name' => 'Stripe', 'logo' => '/images/logos/connections/stripe.png', 'short_description' => 'Payments'],
            ['name' => 'Suivo', 'logo' => '/images/logos/connections/suivo.png', 'short_description' => 'Track & trace'],
            ['name' => 'Teamleader', 'logo' => '/images/logos/connections/teamleader.png', 'short_description' => 'CRM'],
            ['name' => 'Teams', 'logo' => '/images/logos/connections/teams.png', 'short_description' => 'Collaboration'],
            ['name' => 'TMS / Commando Alkon', 'logo' => '/images/logos/connections/tms.png', 'short_description' => 'Concrete plants control'],
            ['name' => 'Twillio', 'logo' => '/images/logos/connections/twillio.png', 'short_description' => 'Communication'],
            ['name' => 'Uniwin', 'logo' => '/images/logos/connections/uniwin.png', 'short_description' => 'Weighbridge'],
            ['name' => 'Vtiger', 'logo' => '/images/logos/connections/v-tiger.png', 'short_description' => 'CRM'],
            ['name' => 'Winbooks', 'logo' => '/images/logos/connections/winbooks.png', 'short_description' => 'Accountancy'],
            ['name' => 'Winkassa', 'logo' => '/images/logos/connections/winkassa.png', 'short_description' => 'Online cash register system'],
            ['name' => 'Wordpress / WooCommerce', 'logo' => '/images/logos/connections/wordpress.png', 'short_description' => 'Blog / webshop'],
            ['name' => 'Yuki', 'logo' => '/images/logos/connections/yuki.png', 'short_description' => 'Accountancy'],
            ['name' => 'Shopify', 'logo' => '/images/logos/connections/shopify.png', 'short_description' => 'Webshop'],
        ];

        foreach ($availableConnections as $availableConnection){
            $connection = Connection::firstOrCreate(
                ['name' => $availableConnection['name']],
                ['short_description' => $availableConnection['short_description']]
            );

            $connection->addMediaFromUrl(URL::to($availableConnection['logo']))
                ->toMediaCollection('logo');
        }
    }
}
