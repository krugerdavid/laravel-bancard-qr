<?php

return [
    'public_key' => env('BANCARDQR_PUBLIC_KEY', null),
    'private_key' => env('BANCARDQR_PRIVATE_KEY', null),
    'service_url' => env('BANCARDQR_SERVICE_URL', null),

    'commerce' => [
        'code' => env('BANCARDQR_COMMERCE_CODE', null),
        'branch' => env('BANCARDQR_COMMERCE_BRANCH', null)
    ]
];