<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\Models\SessionRepository;

final class DeviceSessionService
{
    public function __construct(private readonly SessionRepository $sessionRepository)
    {
    }

    public function register(int $userId, string $sessionId, string $ipAddress, string $userAgent, bool $rememberMe, ?string $rememberSelector = null): void
    {
        $this->sessionRepository->clearCurrentMarker($userId);
        $this->sessionRepository->createDeviceSession([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'remember_selector' => $rememberSelector,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_label' => $this->resolveDeviceLabel($userAgent),
            'is_current' => 1,
            'remember_me' => $rememberMe ? 1 : 0,
            'expires_at' => date('Y-m-d H:i:s', time() + (((int) config('app.session.lifetime', 120)) * 60)),
        ]);
    }

    public function touch(int $userId, string $sessionId): void
    {
        $this->sessionRepository->clearCurrentMarker($userId);
        $this->sessionRepository->touchDeviceSession($sessionId);
    }

    public function revokeCurrent(string $sessionId): void
    {
        $this->sessionRepository->revokeDeviceSessionBySessionId($sessionId);
    }

    public function revokeForUser(int $userId, int $deviceSessionId): void
    {
        $this->sessionRepository->revokeDeviceSessionByIdForUser($userId, $deviceSessionId);
    }

    public function listForUser(int $userId): array
    {
        return $this->sessionRepository->getDeviceSessionsByUser($userId);
    }

    private function resolveDeviceLabel(string $userAgent): string
    {
        if ($userAgent === '') {
            return 'Unknown Device';
        }

        if (stripos($userAgent, 'Windows') !== false) {
            return 'Windows Browser';
        }
        if (stripos($userAgent, 'Android') !== false) {
            return 'Android Device';
        }
        if (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
            return 'iOS Device';
        }

        return 'Web Browser';
    }
}
