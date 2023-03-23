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

    /*
    |--------------------------------------------------------------------------
    | Commerce Info
    |--------------------------------------------------------------------------
    */

    // Commerce Code Identifier provided by Bancard
    'commerce_code' => env('BANCARDQR_COMMERCE_CODE', null),

    // Commerce Branch Identifier provided by Bancard
    'commerce_branch' => env('BANCARDQR_COMMERCE_BRANCH', null),
    
];