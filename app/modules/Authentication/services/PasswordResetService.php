<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\Models\SessionRepository;
use App\Modules\Authentication\Models\TokenRepository;
use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Models\UserRepository;

final class PasswordResetService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TokenRepository $tokenRepository,
        private readonly SessionRepository $sessionRepository,
        private readonly AuthMailService $mailService
    ) {
    }

    public function requestReset(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user instanceof User) {
            return;
        }

        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $rawToken);
        $expiresAt = date('Y-m-d H:i:s', time() + (((int) config('authentication.password_reset_expires_minutes', 120)) * 60));
        $this->tokenRepository->createPasswordResetToken($user->id, $tokenHash, $expiresAt);

        $resetUrl = rtrim((string) config('app.url', ''), '/') . '/auth/reset-password?token=' . urlencode($rawToken);
        $this->mailService->sendPasswordReset($user->email, $user->name, $resetUrl);
    }

    public function reset(string $rawToken, string $newPassword): array
    {
        $token = $this->tokenRepository->findPasswordResetToken(hash('sha256', $rawToken));
        if (!is_array($token)) {
            return ['success' => false, 'message' => 'Token reset tidak valid.'];
        }
        if (strtotime((string) $token['expires_at']) < time()) {
            return ['success' => false, 'message' => 'Token reset sudah kedaluwarsa.'];
        }

        $user = $this->userRepository->findById((int) $token['user_id']);
        if (!$user instanceof User) {
            return ['success' => false, 'message' => 'User reset tidak ditemukan.'];
        }

        $this->userRepository->updatePassword($user->id, password_hash($newPassword, PASSWORD_DEFAULT));
        $this->tokenRepository->markPasswordResetTokenUsed((int) $token['id']);
        $this->sessionRepository->revokeAllRememberTokensByUser($user->id);

        return ['success' => true, 'message' => 'Password berhasil diatur ulang.'];
    }
}
