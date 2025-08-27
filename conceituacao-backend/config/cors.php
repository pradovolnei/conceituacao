<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines which domains are allowed to access your
    | application via HTTP requests from the browser. A wildcard (*) may
    | be used to allow requests from all domains.
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login', 
        'logout', 
        'register', 
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => explode(',', env('APP_CORS_ALLOWED_ORIGINS', 'http://localhost:5173')),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];