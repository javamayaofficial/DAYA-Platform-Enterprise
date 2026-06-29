<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

use App\Core\Modular\BaseModule;
use App\Core\Modular\BaseService;
use App\Modules\Authentication\Models\RoleRepository;
use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Models\UserRepository;

final class RegistrationService extends BaseService
{
    public function __construct(
        BaseModule $module,
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository,
        private readonly VerificationService $verificationService
    ) {
        parent::__construct($module);
    }

    public function register(string $name, string $email, string $password): array
    {
        if ($this->userRepository->findByEmail($email) instanceof User) {
            return ['success' => false, 'message' => 'Email sudah terdaftar.'];
        }

        $userId = $this->userRepository->create([
            'uuid' => $this->generateUuid(),
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'status' => 'pending_verification',
            'email_verified_at' => null,
            'last_login_at' => null,
        ]);

        $this->roleRepository->syncUserRoles($userId, [(string) $this->config('default_registration_role', 'reader')]);
        $user = $this->userRepository->findById($userId);
        if ($user instanceof User) {
            $this->verificationService->issue($user);
        }

        return ['success' => true, 'message' => 'Registrasi berhasil. Silakan cek email verifikasi Anda.'];
    }

    private function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
