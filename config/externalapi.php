<?php

return [

    /*
    |--------------------------------------------------------------------------
    | External API Base URL
    |--------------------------------------------------------------------------
    |
    | This value is the base URL for your external rule service.
    | It is loaded from the .env file using the key:
    | EXTERNAL_API_BASE_URL=http://example.com
    |
    | Always access it using: config('externalapi.base_url')
    | NEVER use env() inside controllers after config caching.
    |
    */

    'base_url' => env('EXTERNAL_API_BASE_URL', 'https://api.rag-ai.org'),

    /*
    |--------------------------------------------------------------------------
    | External API Timeout (optional)
    |--------------------------------------------------------------------------
    |
    | Default timeout for API requests in seconds.
    | You can adjust this if needed.
    |
    */

    'timeout' => env('EXTERNAL_API_TIMEOUT', 60),

];
