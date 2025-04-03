<?php

return [
    /**
     * Fastly API KEY
     */
    'api_key' => env('FASTLY_API_KEY', ''),

    /**
     * Fastly Endpoint
     */
    'endpoint' => env('FASTLY_ENDPOINT', 'https://api.fastly.com/purge'),

    /**
     * Enable ESI
     */
    'esi' => env('FASTLY_ESI_ENABLED', false),

    /**
     * Default cache TTL in seconds
     */
    'default_ttl' => env('FASTLY_DEFAULT_TTL', 0),

    /**
     * Default cache storage
     * private,no-cache,public,no-vary
     */
    'default_cacheability' => env('FASTLY_DEFAULT_CACHEABILITY', 'no-cache'),

    /**
     * Guest only mode (Do not cache logged in users)
     */
     'guest_only' => env('FASTLY_GUEST_ONLY', false),
];
