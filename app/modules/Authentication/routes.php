<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Modules\Authentication\Controllers\AuthController;
use App\Modules\Authentication\Controllers\RbacController;
use App\Modules\Authentication\Controllers\SecurityController;
use App\Modules\Authentication\Middleware\GuestMiddleware;
use App\Modules\Authentication\Middleware\PermissionMiddleware;

$authController = new AuthController();
$securityController = new SecurityController();
$rbacController = new RbacController();

$router->get('/auth/register', [$authController, 'showRegister'], [GuestMiddleware::class]);
$router->post('/auth/register', [$authController, 'register'], [GuestMiddleware::class, CsrfMiddleware::class]);
$router->get('/auth/login', [$authController, 'showLogin'], [GuestMiddleware::class]);
$router->post('/auth/login', [$authController, 'login'], [GuestMiddleware::class, CsrfMiddleware::class]);
$router->post('/auth/logout', [$authController, 'logout'], [AuthMiddleware::class, CsrfMiddleware::class]);

$router->get('/auth/forgot-password', [$authController, 'showForgotPassword'], [GuestMiddleware::class]);
$router->post('/auth/forgot-password', [$authController, 'sendForgotPassword'], [GuestMiddleware::class, CsrfMiddleware::class]);
$router->get('/auth/reset-password', [$authController, 'showResetPassword'], [GuestMiddleware::class]);
$router->post('/auth/reset-password', [$authController, 'resetPassword'], [GuestMiddleware::class, CsrfMiddleware::class]);

$router->get('/auth/verify', [$authController, 'verifyEmail'], [GuestMiddleware::class]);
$router->get('/auth/verify-notice', [$authController, 'showVerifyNotice'], [GuestMiddleware::class]);
$router->post('/auth/verify-notice', [$authController, 'resendVerification'], [GuestMiddleware::class, CsrfMiddleware::class]);

$router->get('/auth/security', [$securityController, 'dashboard'], [AuthMiddleware::class]);
$router->get('/auth/security/sessions', [$securityController, 'deviceSessions'], [AuthMiddleware::class, [PermissionMiddleware::class, 'auth.security.device-sessions.manage']]);
$router->post('/auth/security/sessions/revoke', [$securityController, 'revokeDeviceSession'], [AuthMiddleware::class, CsrfMiddleware::class, [PermissionMiddleware::class, 'auth.security.device-sessions.manage']]);
$router->get('/auth/security/login-history', [$securityController, 'loginHistory'], [AuthMiddleware::class, [PermissionMiddleware::class, 'auth.security.login-history.view']]);

$router->get('/auth/admin/users', [$rbacController, 'users'], [AuthMiddleware::class, [PermissionMiddleware::class, 'auth.users.view']]);
$router->get('/auth/admin/users/{id}/roles', [$rbacController, 'editRoles'], [AuthMiddleware::class, [PermissionMiddleware::class, 'auth.users.roles.manage']]);
$router->post('/auth/admin/users/{id}/roles', [$rbacController, 'updateRoles'], [AuthMiddleware::class, CsrfMiddleware::class, [PermissionMiddleware::class, 'auth.users.roles.manage']]);
$router->get('/auth/admin/roles', [$rbacController, 'roleMatrix'], [AuthMiddleware::class, [PermissionMiddleware::class, 'auth.roles.permissions.view']]);
