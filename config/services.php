<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'teams' => [
        'webhook_url' => env('TEAMS_WEBHOOK_URL'),
    ],

    'google' => [
        'maps' => env('GOOGLE_MAPS_API')
    ],

    'clearfacts' => [
        'url' => env('CLEARFACTS_URL'),
        'vat' => env('CLEARFACTS_VAT'),
        'token' => env('CLEARFACTS_TOKEN')
    ],

    'github' => [
        'url' => env('GITHUB_URL'),
        'token' => env('GITHUB_TOKEN'),
        'project_repo' => env('GITHUB_PROJECT_REPO')
    ],

    'recommand' => [
        'api_key' => env('RECOMMAND_API_KEY'),
        'api_secret' => env('RECOMMAND_API_SECRET'),
        'base_url' => env('RECOMMAND_BASE_URL', 'https://peppol.recommand.eu'),
        'team_id' => env('RECOMMAND_TEAM_ID'),
        'company_id' => env('RECOMMAND_COMPANY_ID'),
        'identifier_mode' => env('RECOMMAND_IDENTIFIER_MODE', 'alternative'),
        'seller' => [
            'name' => env('RECOMMAND_SELLER_NAME'),
            'street' => env('RECOMMAND_SELLER_STREET'),
            'city' => env('RECOMMAND_SELLER_CITY'),
            'postal_zone' => env('RECOMMAND_SELLER_POSTAL'),
            'country' => env('RECOMMAND_SELLER_COUNTRY', 'BE'),
            'vat_number' => env('RECOMMAND_SELLER_VAT'),
            'iban' => env('RECOMMAND_SELLER_IBAN'),
        ],
    ],
];
