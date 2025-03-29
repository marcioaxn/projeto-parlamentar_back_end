<?php

use Illuminate\Support\Str;

return [

    'driver' => 'database',

    'lifetime' => env('SESSION_LIFETIME', 180),

    'expire_on_close' => true,

    'encrypt' => true,

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION', null),

    'table' => 'sessions',

    'store' => env('SESSION_STORE', null),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
    ),

    'path' => '/',

    // 'domain' => '45.55.78.159',
    'domain' => env('SESSION_DOMAIN', null),

    'secure' => env('SESSION_SECURE_COOKIE'),

    'http_only' => true,

    'same_site' => 'strict',

];
