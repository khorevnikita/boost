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

    'ecommpay' => [
        'id' => env('ECOMMPAY_ID'),
        'secret' => env('ECOMMPAY_SECRET')
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/login/facebook/callback',
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . "/login/google/callback",
    ],

    'steam' => [
        'client_id' => env('STEAM_CLIENT_ID'),
        'client_secret' => env('STEAM_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/login/steam/callback',
        'api_key' => env('STEAM_API_KEY'),
    ],

    'bitcoin' => [
        'hash' => env("BITCOIN_HASH")
    ],
    'payapp' => [
        'key' => env("PAYAPP_KEY")
    ],
    'stripe' => [
        'public' => env("STRIPE_PUBLIC"),
        'key' => env("STRIPE_KEY")
    ],
    'paypal' => [
        'id' => env("PAYPAL_ID"),
        'secret' => env("PAYPAL_KEY"),
    ]
];
