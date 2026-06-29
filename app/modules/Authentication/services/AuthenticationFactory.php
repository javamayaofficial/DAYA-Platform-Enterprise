<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

use App\Core\Database;
use App\Core\Modular\BaseModule;
use App\Core\Modular\BaseService;
use App\Modules\Authentication\Models\LoginHistoryRepository;
use App\Modules\Authentication\Models\RoleRepository;
use App\Modules\Authentication\Models\SessionRepository;
use App\Modules\Authentication\Models\TokenRepository;
use App\Modules\Authentication\Models\UserRepository;
use PDO;

final class AuthenticationFactory extends BaseService
{
    public function __construct(BaseModule $module)
    {
        parent::__construct($module);
    }

    public function pdo(): PDO
    {
        return Database::connection();
    }

    public function userRepository(): UserRepository
    {
        return new UserRepository($this->pdo());
    }

    public function roleRepository(): RoleRepository
    {
        return new RoleRepository($this->pdo());
    }

    public function tokenRepository(): TokenRepository
    {
        return new TokenRepository($this->pdo());
    }

    public function sessionRepository(): SessionRepository
    {
        return new SessionRepository($this->pdo());
    }

    public function loginHistoryRepository(): LoginHistoryRepository
    {
        return new LoginHistoryRepository($this->pdo());
    }

    public function validator(): AuthValidator
    {
        return new AuthValidator();
    }

    public function mailService(): AuthMailService
    {
        return new AuthMailService();
    }

    public function verificationService(): VerificationService
    {
        return new VerificationService($this->tokenRepository(), $this->userRepository(), $this->mailService());
    }

    public function passwordResetService(): PasswordResetService
    {
        return new PasswordResetService($this->userRepository(), $this->tokenRepository(), $this->sessionRepository(), $this->mailService());
    }

    public function registrationService(): RegistrationService
    {
        return new RegistrationService($this->module(), $this->userRepository(), $this->roleRepository(), $this->verificationService());
    }

    public function rbacService(): RbacService
    {
        return new RbacService($this->roleRepository());
    }

    public function deviceSessionService(): DeviceSessionService
    {
        return new DeviceSessionService($this->sessionRepository());
    }

    public function authService(): AuthService
    {
        return new AuthService(
            $this->userRepository(),
            $this->roleRepository(),
            $this->sessionRepository(),
            $this->loginHistoryRepository(),
            $this->rbacService(),
            $this->deviceSessionService()
        );
    }
}
