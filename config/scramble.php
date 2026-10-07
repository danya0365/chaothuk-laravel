<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Documentation
    |--------------------------------------------------------------------------
    |
    | Scramble generates the OpenAPI document for the mobile API from the route
    | definitions. Only the versioned /api/v1 surface is exposed — the browser-only
    | /ajax and /frontend routes are not part of the client's contract.
    |
    */

    'api_path' => 'api/v1',

    'export_path' => 'api.json',

    'ui' => [
        'title' => 'Chaothuk API',

        'theme' => 'light',

        'hide_try_it' => false,

        'layout' => 'responsive',

        'persist_authorization' => true,

        'hide_schemas' => false,

        'logo' => '',
    ],

    'servers' => [
        env('APP_URL').'/api/v1',
    ],
];