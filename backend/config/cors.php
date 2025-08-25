<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'auth/*', 'products', 'categories', '*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['https://indopet.vercel.app'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
