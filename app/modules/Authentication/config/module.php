<?php

declare(strict_types=1);

return [
    'default_registration_role' => 'reader',
    'verification_expires_minutes' => 1440,
    'password_reset_expires_minutes' => 120,
    'remember_lifetime_days' => 30,
    'login_rate_limit_attempts' => 5,
    'login_rate_limit_window_minutes' => 15,
    'mail_mode' => env('AUTH_MAIL_MODE', 'log'),
    'mail_from' => env('AUTH_MAIL_FROM', 'no-reply@daya.local'),
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
