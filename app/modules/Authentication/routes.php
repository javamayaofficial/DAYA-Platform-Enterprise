<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Modules\Authentication\Controllers\AuthController;
use App\Modules\Authentication\Controllers\RbacController;
use App\Modules\Authentication\Controllers\SecurityController;
use App\Modules\Authentication\Middleware\GuestMiddleware;
use App\Modules\Authentication\Middleware\PermissionMiddleware;

$authController = new AuthController();
$securityController = new SecurityController();
$rbacController = new RbacController();
$authRateLimits = (array) config('authentication.route_rate_limits', []);
$registerRateLimit = (array) ($authRateLimits['register'] ?? []);
$loginRateLimit = (array) ($authRateLimits['login'] ?? []);
$forgotPasswordRateLimit = (array) ($authRateLimits['forgot_password'] ?? []);
$resetPasswordRateLimit = (array) ($authRateLimits['reset_password'] ?? []);
$verifyNoticeRateLimit = (array) ($authRateLimits['verify_notice'] ?? []);
$logoutRateLimit = (array) ($authRateLimits['logout'] ?? []);

$router->get('/auth/register', [$authController, 'showRegister'], [GuestMiddleware::class]);
$router->post('/auth/register', [$authController, 'register'], [
    GuestMiddleware::class,
    CsrfMiddleware::class,
    [RateLimitMiddleware::class, 'auth-register', (int) ($registerRateLimit['attempts'] ?? 10), (int) ($registerRateLimit['window_seconds'] ?? 300)],
]);
$router->get('/auth/login', [$authController, 'showLogin'], [GuestMiddleware::class]);
$router->post('/auth/login', [$authController, 'login'], [
    GuestMiddleware::class,
    CsrfMiddleware::class,
    [RateLimitMiddleware::class, 'auth-login', (int) ($loginRateLimit['attempts'] ?? 10), (int) ($loginRateLimit['window_seconds'] ?? 300)],
]);
$router->post('/auth/logout', [$authController, 'logout'], [
    AuthMiddleware::class,
    CsrfMiddleware::class,
    [RateLimitMiddleware::class, 'auth-logout', (int) ($logoutRateLimit['attempts'] ?? 20), (int) ($logoutRateLimit['window_seconds'] ?? 300)],
]);

$router->get('/auth/forgot-password', [$authController, 'showForgotPassword'], [GuestMiddleware::class]);
$router->post('/auth/forgot-password', [$authController, 'sendForgotPassword'], [
    GuestMiddleware::class,
    CsrfMiddleware::class,
    [RateLimitMiddleware::class, 'auth-forgot-password', (int) ($forgotPasswordRateLimit['attempts'] ?? 5), (int) ($forgotPasswordRateLimit['window_seconds'] ?? 600)],
]);
$router->get('/auth/reset-password', [$authController, 'showResetPassword'], [GuestMiddleware::class]);
$router->post('/auth/reset-password', [$authController, 'resetPassword'], [
    GuestMiddleware::class,
    CsrfMiddleware::class,
    [RateLimitMiddleware::class, 'auth-reset-password', (int) ($resetPasswordRateLimit['attempts'] ?? 5), (int) ($resetPasswordRateLimit['window_seconds'] ?? 600)],
]);

$router->get('/auth/verify', [$authController, 'verifyEmail'], [GuestMiddleware::class]);
$router->get('/auth/verify-notice', [$authController, 'showVerifyNotice'], [GuestMiddleware::class]);
$router->post('/auth/verify-notice', [$authController, 'resendVerification'], [
    GuestMiddleware::class,
    CsrfMiddleware::class,
    [RateLimitMiddleware::class, 'auth-verify-notice', (int) ($verifyNoticeRateLimit['attempts'] ?? 5), (int) ($verifyNoticeRateLimit['window_seconds'] ?? 600)],
]);

$router->get('/auth/security', [$securityController, 'dashboard'], [AuthMiddleware::class]);
$router->get('/auth/security/sessions', [$securityController, 'deviceSessions'], [AuthMiddleware::class, [PermissionMiddleware::class, 'auth.security.device-sessions.manage']]);
$router->post('/auth/security/sessions/revoke', [$securityController, 'revokeDeviceSession'], [AuthMiddleware::class, CsrfMiddleware::class, [PermissionMiddleware::class, 'auth.security.device-sessions.manage']]);
$router->get('/auth/security/login-history', [$securityController, 'loginHistory'], [AuthMiddleware::class, [PermissionMiddleware::class, 'auth.security.login-history.view']]);

$router->get('/auth/admin/users', [$rbacController, 'users'], [AuthMiddleware::class, [PermissionMiddleware::class, 'auth.users.view']]);
$router->get('/auth/admin/users/{id}/roles', [$rbacController, 'editRoles'], [AuthMiddleware::class, [PermissionMiddleware::class, 'auth.users.roles.manage']]);
$router->post('/auth/admin/users/{id}/roles', [$rbacController, 'updateRoles'], [AuthMiddleware::class, CsrfMiddleware::class, [PermissionMiddleware::class, 'auth.users.roles.manage']]);
$router->get('/auth/admin/roles', [$rbacController, 'roleMatrix'], [AuthMiddleware::class, [PermissionMiddleware::class, 'auth.roles.permissions.view']]);
