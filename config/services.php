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

    'who_icd' => [
        'client_id' => env('WHO_ICD_CLIENT_ID'),
        'client_secret' => env('WHO_ICD_CLIENT_SECRET'),
        'token_url' => env(
            'WHO_ICD_TOKEN_URL',
            'https://icdaccessmanagement.who.int/connect/token'
        ),
        'api_url' => env('WHO_ICD_API_URL', 'https://id.who.int'),
    ],

];
