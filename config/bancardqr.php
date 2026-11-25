<?php

return [
    'public_key' => env('BANCARDQR_PUBLIC_KEY', null),
    'private_key' => env('BANCARDQR_PRIVATE_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Bancard Environment
    |--------------------------------------------------------------------------
    |
    | This value determines if your application is using the 
    | staging environment from Bancard's API.
    |
    */
    'staging' => (bool) env('BANCARDQR_STAGING', true),

    // Override base URL (optional). Si está vacío, usa STAGING_URL o PRODUCTION_URL según staging.
    'url' => env('BANCARDQR_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Commerce Info
    |--------------------------------------------------------------------------
    */

    // Commerce Code Identifier provided by Bancard
    'commerce_code' => env('BANCARDQR_COMMERCE_CODE', null),

    // Commerce Branch Identifier provided by Bancard
    'commerce_branch' => env('BANCARDQR_COMMERCE_BRANCH', null),

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout in seconds for API requests.
    |
    */
    'timeout' => env('BANCARDQR_TIMEOUT', 30),
];