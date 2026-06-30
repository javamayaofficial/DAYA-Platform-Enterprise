<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'DAYA Platform'),
    'env' => env('APP_ENV', 'production'),
    'debug' => boolean_env('APP_DEBUG', false),
    'url' => env('APP_URL', 'https://localhost'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'installed' => boolean_env('APP_INSTALLED', false),
    'key' => env('APP_KEY', ''),
    'log_level' => env('APP_LOG_LEVEL', 'info'),
    'session' => [
        'name' => env('SESSION_NAME', 'daya_session'),
        'lifetime' => (int) env('SESSION_LIFETIME', 120),
        'secure' => boolean_env('SESSION_SECURE', (string) env('APP_ENV', 'production') === 'production'),
        'http_only' => boolean_env('SESSION_HTTP_ONLY', true),
        'same_site' => env('SESSION_SAME_SITE', 'Lax'),
        'save_path' => env('SESSION_SAVE_PATH', ''),
    ],
    'security' => [
        'headers_enabled' => boolean_env('SECURITY_HEADERS_ENABLED', true),
        'hsts_enabled' => boolean_env('SECURITY_HSTS_ENABLED', false),
        'hsts_max_age' => (int) env('SECURITY_HSTS_MAX_AGE', 31536000),
        'frame_options' => env('SECURITY_FRAME_OPTIONS', 'SAMEORIGIN'),
        'content_type_options' => env('SECURITY_CONTENT_TYPE_OPTIONS', 'nosniff'),
        'referrer_policy' => env('SECURITY_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
        'permissions_policy' => env('SECURITY_PERMISSIONS_POLICY', 'camera=(), microphone=(), geolocation=()'),
        'cross_domain_policies' => env('SECURITY_CROSS_DOMAIN_POLICIES', 'none'),
    ],
    'whatsapp' => [
        'mode' => env('WHATSAPP_MODE', 'off'),
        'default_country_code' => env('WHATSAPP_DEFAULT_COUNTRY_CODE', '62'),
        'admin_targets' => array_values(array_filter(array_map(
            static fn (string $item): string => trim($item),
            explode(',', (string) env('WHATSAPP_ADMIN_TARGETS', ''))
        ))),
        'events' => [
            'admin_creator_registration' => boolean_env('WHATSAPP_EVENT_ADMIN_CREATOR_REGISTRATION', true),
            'admin_creator_review' => boolean_env('WHATSAPP_EVENT_ADMIN_CREATOR_REVIEW', true),
        ],
        'fonnte' => [
            'endpoint' => env('WHATSAPP_FONNTE_ENDPOINT', 'https://api.fonnte.com/send'),
            'token' => env('WHATSAPP_FONNTE_TOKEN', ''),
            'timeout_seconds' => (int) env('WHATSAPP_FONNTE_TIMEOUT', 15),
            'typing' => boolean_env('WHATSAPP_FONNTE_TYPING', false),
            'connect_only' => boolean_env('WHATSAPP_FONNTE_CONNECT_ONLY', true),
        ],
    ],
];
