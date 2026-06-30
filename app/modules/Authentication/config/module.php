<?php

declare(strict_types=1);

return [
    'default_registration_role' => 'reader',
    'verification_expires_minutes' => 1440,
    'password_reset_expires_minutes' => 120,
    'remember_lifetime_days' => 30,
    'login_rate_limit_attempts' => 5,
    'login_rate_limit_window_minutes' => 15,
    'route_rate_limits' => [
        'register' => ['attempts' => 10, 'window_seconds' => 300],
        'login' => ['attempts' => 10, 'window_seconds' => 300],
        'forgot_password' => ['attempts' => 5, 'window_seconds' => 600],
        'reset_password' => ['attempts' => 5, 'window_seconds' => 600],
        'verify_notice' => ['attempts' => 5, 'window_seconds' => 600],
        'logout' => ['attempts' => 20, 'window_seconds' => 300],
    ],
    'mail_mode' => env('AUTH_MAIL_MODE', 'log'),
    'mail_from' => env('AUTH_MAIL_FROM', 'no-reply@daya.local'),
    'mailketing' => [
        'endpoint' => env('AUTH_MAILKETING_ENDPOINT', 'https://api.mailketing.co.id/api/v1/send'),
        'api_token' => env('AUTH_MAILKETING_API_TOKEN', ''),
        'from_name' => env('AUTH_MAILKETING_FROM_NAME', env('APP_NAME', 'DAYA Platform')),
        'from_email' => env('AUTH_MAILKETING_FROM_EMAIL', env('AUTH_MAIL_FROM', 'no-reply@daya.local')),
        'timeout_seconds' => (int) env('AUTH_MAILKETING_TIMEOUT', 15),
    ],
    'remember_cookie_name' => env('AUTH_REMEMBER_COOKIE', 'daya_remember'),
    'session_cookie_name' => env('SESSION_NAME', 'daya_session'),
    'roles' => [
        'super_admin' => 'Super Admin',
        'admin_yayasan' => 'Admin Yayasan',
        'creator' => 'Creator',
        'listener' => 'Listener',
        'reader' => 'Reader',
        'sponsor' => 'Sponsor',
    ],
    'permissions' => [
        'auth.security.view',
        'auth.security.device-sessions.manage',
        'auth.security.login-history.view',
        'auth.users.view',
        'auth.users.roles.manage',
        'auth.roles.permissions.view',
        'platform.admin.access',
    ],
];
