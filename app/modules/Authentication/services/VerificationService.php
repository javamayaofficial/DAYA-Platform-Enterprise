<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\Models\TokenRepository;
use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Models\UserRepository;

final class VerificationService
{
    public function __construct(
        private readonly TokenRepository $tokenRepository,
        private readonly UserRepository $userRepository,
        private readonly AuthMailService $mailService
    ) {
    }

    public function issue(User $user): void
    {
        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $rawToken);
        $expiresAt = date('Y-m-d H:i:s', time() + (((int) config('authentication.verification_expires_minutes', 1440)) * 60));
        $this->tokenRepository->createEmailVerificationToken($user->id, $tokenHash, $expiresAt);

        $verificationUrl = rtrim((string) config('app.url', ''), '/') . '/auth/verify?token=' . urlencode($rawToken);
        $this->mailService->sendVerification($user->email, $user->name, $verificationUrl);
    }

    public function verify(string $rawToken): array
    {
        $token = $this->tokenRepository->findEmailVerificationToken(hash('sha256', $rawToken));
        if (!is_array($token)) {
            return ['success' => false, 'message' => 'Token verifikasi tidak valid.'];
        }
        if (strtotime((string) $token['expires_at']) < time()) {
            return ['success' => false, 'message' => 'Token verifikasi sudah kedaluwarsa.'];
        }

        $user = $this->userRepository->findById((int) $token['user_id']);
        if (!$user instanceof User) {
            return ['success' => false, 'message' => 'User verifikasi tidak ditemukan.'];
        }

        $this->userRepository->markEmailVerified($user->id);
        $this->tokenRepository->markEmailVerificationTokenUsed((int) $token['id']);

        return ['success' => true, 'message' => 'Email berhasil diverifikasi.'];
    }
}
