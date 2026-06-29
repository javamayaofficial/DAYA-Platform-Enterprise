<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'DAYA Platform'),
    'env' => env('APP_ENV', 'production'),
    'debug' => boolean_env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'installed' => boolean_env('APP_INSTALLED', false),
    'key' => env('APP_KEY', ''),
    'log_level' => env('APP_LOG_LEVEL', 'debug'),
    'session' => [
        'name' => env('SESSION_NAME', 'daya_session'),
        'lifetime' => (int) env('SESSION_LIFETIME', 120),
        'secure' => boolean_env('SESSION_SECURE', false),
        'http_only' => boolean_env('SESSION_HTTP_ONLY', true),
        'same_site' => env('SESSION_SAME_SITE', 'Lax'),
    ],
];
