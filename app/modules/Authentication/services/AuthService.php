<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

use App\Core\Http\Request;
use App\Modules\Authentication\Models\LoginHistoryRepository;
use App\Modules\Authentication\Models\RoleRepository;
use App\Modules\Authentication\Models\SessionRepository;
use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Models\UserRepository;

final class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository,
        private readonly SessionRepository $sessionRepository,
        private readonly LoginHistoryRepository $loginHistoryRepository,
        private readonly RbacService $rbacService,
        private readonly DeviceSessionService $deviceSessionService
    ) {
    }

    public function attemptLogin(string $email, string $password, bool $rememberMe, Request $request): array
    {
        if ($this->isRateLimited($email, $request->ip())) {
            return ['success' => false, 'message' => 'Terlalu banyak percobaan login. Coba lagi beberapa saat.'];
        }

        $user = $this->userRepository->findByEmail($email);
        if (!$user instanceof User || !password_verify($password, $user->passwordHash)) {
            $this->recordLoginAttempt(null, $email, $request, 'failed', 'Kredensial tidak valid.');
            return ['success' => false, 'message' => 'Email atau password salah.'];
        }

        if ($user->status === 'pending_verification' || $user->emailVerifiedAt === null) {
            $this->recordLoginAttempt($user->id, $email, $request, 'blocked', 'Email belum diverifikasi.');
            return ['success' => false, 'message' => 'Akun belum diverifikasi. Silakan cek email Anda.'];
        }
        if (in_array($user->status, ['suspended', 'deactivated', 'banned'], true)) {
            $this->recordLoginAttempt($user->id, $email, $request, 'blocked', 'Status akun tidak mengizinkan login.');
            return ['success' => false, 'message' => 'Akun tidak dapat login karena statusnya tidak aktif.'];
        }

        $this->establishSession($user, $request, $rememberMe);
        $this->userRepository->updateLastLogin($user->id);
        $this->recordLoginAttempt($user->id, $email, $request, 'success', null);

        return ['success' => true, 'message' => 'Login berhasil.'];
    }

    public function logout(Request $request): void
    {
        $auth = $request->session()->get('auth', []);
        $selector = (string) ($auth['remember_selector'] ?? '');
        $sessionId = session_id();

        if ($selector !== '') {
            $remember = $this->sessionRepository->findRememberToken($selector);
            if (is_array($remember)) {
                $this->sessionRepository->revokeRememberToken((int) $remember['id']);
            }
        }

        if ($sessionId !== '') {
            $this->deviceSessionService->revokeCurrent($sessionId);
        }

        $request->session()->invalidate();
        $this->clearRememberCookie();
    }

    public function restoreFromRememberCookie(string $cookieValue, Request $request): bool
    {
        [$selector, $validator] = array_pad(explode(':', $cookieValue, 2), 2, '');
        if ($selector === '' || $validator === '') {
            $this->clearRememberCookie();
            return false;
        }

        $remember = $this->sessionRepository->findRememberToken($selector);
        if (!is_array($remember)) {
            $this->clearRememberCookie();
            return false;
        }
        if (!hash_equals((string) $remember['validator_hash'], hash('sha256', $validator)) || strtotime((string) $remember['expires_at']) < time()) {
            $this->sessionRepository->revokeRememberToken((int) $remember['id']);
            $this->clearRememberCookie();
            return false;
        }

        $user = $this->userRepository->findById((int) $remember['user_id']);
        if (!$user instanceof User || !$user->isActive()) {
            $this->sessionRepository->revokeRememberToken((int) $remember['id']);
            $this->clearRememberCookie();
            return false;
        }

        $this->sessionRepository->revokeRememberToken((int) $remember['id']);
        $this->establishSession($user, $request, true);

        return true;
    }

    public function refreshSessionAuth(Request $request): void
    {
        $auth = $request->session()->get('auth', []);
        if (!is_array($auth) || !isset($auth['user_id'])) {
            return;
        }

        $user = $this->userRepository->findById((int) $auth['user_id']);
        if (!$user instanceof User || !$user->isActive()) {
            $this->logout($request);
            return;
        }

        $context = $this->rbacService->resolveAuthContext($user->id);
        $request->session()->set('auth', array_merge($auth, [
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'roles' => $context['roles'],
            'permissions' => $context['permissions'],
        ]));

        $this->deviceSessionService->touch($user->id, session_id());
    }

    private function establishSession(User $user, Request $request, bool $rememberMe): void
    {
        $request->session()->regenerate();
        $context = $this->rbacService->resolveAuthContext($user->id);
        $rememberSelector = null;

        if ($rememberMe) {
            $rememberSelector = $this->issueRememberCookie($user->id);
        } else {
            $this->clearRememberCookie();
        }

        $request->session()->set('auth', [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'status' => $user->status,
            'roles' => $context['roles'],
            'permissions' => $context['permissions'],
            'remember_selector' => $rememberSelector,
        ]);

        $this->deviceSessionService->register(
            $user->id,
            session_id(),
            $request->ip(),
            (string) $request->server('HTTP_USER_AGENT', ''),
            $rememberMe,
            $rememberSelector
        );
    }

    private function issueRememberCookie(int $userId): string
    {
        $selector = bin2hex(random_bytes(8));
        $validator = bin2hex(random_bytes(32));
        $validatorHash = hash('sha256', $validator);
        $days = (int) config('authentication.remember_lifetime_days', 30);
        $expiresAt = time() + ($days * 86400);

        $this->sessionRepository->createRememberToken($userId, $selector, $validatorHash, date('Y-m-d H:i:s', $expiresAt));

        setcookie((string) config('authentication.remember_cookie_name', 'daya_remember'), $selector . ':' . $validator, [
            'expires' => $expiresAt,
            'path' => '/',
            'secure' => (bool) config('app.session.secure', false),
            'httponly' => true,
            'samesite' => (string) config('app.session.same_site', 'Lax'),
        ]);

        return $selector;
    }

    private function clearRememberCookie(): void
    {
        setcookie((string) config('authentication.remember_cookie_name', 'daya_remember'), '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => (bool) config('app.session.secure', false),
            'httponly' => true,
            'samesite' => (string) config('app.session.same_site', 'Lax'),
        ]);
    }

    private function isRateLimited(string $email, string $ipAddress): bool
    {
        return $this->loginHistoryRepository->countRecentFailures(
            $email,
            $ipAddress,
            (int) config('authentication.login_rate_limit_window_minutes', 15)
        ) >= (int) config('authentication.login_rate_limit_attempts', 5);
    }

    private function recordLoginAttempt(?int $userId, string $email, Request $request, string $status, ?string $failureReason): void
    {
        $this->loginHistoryRepository->record([
            'user_id' => $userId,
            'attempted_email' => strtolower($email),
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->server('HTTP_USER_AGENT', ''),
            'status' => $status,
            'failure_reason' => $failureReason,
        ]);
    }
}
