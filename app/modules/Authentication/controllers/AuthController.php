<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Requests\AuthRequest;
use App\Modules\Authentication\Responses\AuthResponse;

final class AuthController extends AbstractAuthenticationController
{
    public function showRegister(Request $request): Response
    {
        return $this->render('auth/register', 'Register', [
            'errors' => [],
            'old' => [],
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }

    public function register(Request $request): Response
    {
        $authRequest = AuthRequest::from($request);
        $validation = $this->factory->validator()->validateRegistration($authRequest->all());
        if ($validation['errors'] !== []) {
            return $this->render('auth/register', 'Register', [
                'errors' => $validation['errors'],
                'old' => $authRequest->all(),
                'flash' => null,
            ], 422);
        }

        $result = $this->factory->registrationService()->register(
            (string) $validation['data']['name'],
            (string) $validation['data']['email'],
            (string) $validation['data']['password']
        );

        if (!$result['success']) {
            return $this->render('auth/register', 'Register', [
                'errors' => ['email' => (string) $result['message']],
                'old' => $authRequest->all(),
                'flash' => null,
            ], 422);
        }

        return AuthResponse::redirect('/auth/verify-notice?email=' . urlencode((string) $validation['data']['email']));
    }

    public function showLogin(Request $request): Response
    {
        return $this->render('auth/login', 'Login', [
            'errors' => [],
            'old' => [],
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }

    public function login(Request $request): Response
    {
        $authRequest = AuthRequest::from($request);
        $validation = $this->factory->validator()->validateLogin($authRequest->all());
        if ($validation['errors'] !== []) {
            return $this->render('auth/login', 'Login', [
                'errors' => $validation['errors'],
                'old' => $authRequest->all(),
                'flash' => null,
            ], 422);
        }

        $result = $this->factory->authService()->attemptLogin(
            (string) $validation['data']['email'],
            (string) $validation['data']['password'],
            (bool) $validation['data']['remember'],
            $request
        );

        if (!$result['success']) {
            return $this->render('auth/login', 'Login', [
                'errors' => ['email' => (string) $result['message']],
                'old' => $authRequest->all(),
                'flash' => null,
            ], 422);
        }

        $request->session()->flash('auth.status', ['type' => 'success', 'message' => 'Login berhasil.']);

        return AuthResponse::redirect('/auth/security');
    }

    public function logout(Request $request): Response
    {
        $this->factory->authService()->logout($request);
        $request->session()->start();
        $request->session()->flash('auth.status', ['type' => 'success', 'message' => 'Logout berhasil.']);

        return AuthResponse::redirect('/auth/login');
    }

    public function showForgotPassword(Request $request): Response
    {
        return $this->render('auth/forgot-password', 'Forgot Password', [
            'errors' => [],
            'old' => [],
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }

    public function sendForgotPassword(Request $request): Response
    {
        $authRequest = AuthRequest::from($request);
        $validation = $this->factory->validator()->validateForgotPassword($authRequest->all());
        if ($validation['errors'] !== []) {
            return $this->render('auth/forgot-password', 'Forgot Password', [
                'errors' => $validation['errors'],
                'old' => $authRequest->all(),
                'flash' => null,
            ], 422);
        }

        $this->factory->passwordResetService()->requestReset((string) $validation['data']['email']);

        return $this->render('auth/forgot-password', 'Forgot Password', [
            'errors' => [],
            'old' => [],
            'flash' => ['type' => 'success', 'message' => 'Jika email terdaftar, tautan reset password telah dikirim.'],
        ]);
    }

    public function showResetPassword(Request $request): Response
    {
        return $this->render('auth/reset-password', 'Reset Password', [
            'errors' => [],
            'old' => ['token' => (string) $request->query('token', '')],
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }

    public function resetPassword(Request $request): Response
    {
        $authRequest = AuthRequest::from($request);
        $validation = $this->factory->validator()->validateResetPassword($authRequest->all());
        if ($validation['errors'] !== []) {
            return $this->render('auth/reset-password', 'Reset Password', [
                'errors' => $validation['errors'],
                'old' => $authRequest->all(),
                'flash' => null,
            ], 422);
        }

        $result = $this->factory->passwordResetService()->reset(
            (string) $validation['data']['token'],
            (string) $validation['data']['password']
        );

        if (!$result['success']) {
            return $this->render('auth/reset-password', 'Reset Password', [
                'errors' => ['token' => (string) $result['message']],
                'old' => $authRequest->all(),
                'flash' => null,
            ], 422);
        }

        $request->session()->flash('auth.status', ['type' => 'success', 'message' => 'Password berhasil direset. Silakan login.']);

        return AuthResponse::redirect('/auth/login');
    }

    public function verifyEmail(Request $request): Response
    {
        $result = $this->factory->verificationService()->verify((string) $request->query('token', ''));

        return $this->render('auth/verify-result', 'Verifikasi Email', [
            'result' => $result,
        ], $result['success'] ? 200 : 422);
    }

    public function showVerifyNotice(Request $request): Response
    {
        return $this->render('auth/verify-notice', 'Verifikasi Email', [
            'errors' => [],
            'old' => ['email' => (string) $request->query('email', '')],
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }

    public function resendVerification(Request $request): Response
    {
        $authRequest = AuthRequest::from($request);
        $email = $authRequest->email();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('auth/verify-notice', 'Verifikasi Email', [
                'errors' => ['email' => 'Email tidak valid.'],
                'old' => $authRequest->all(),
                'flash' => null,
            ], 422);
        }

        $user = $this->factory->userRepository()->findByEmail($email);
        if ($user instanceof User && $user->emailVerifiedAt === null) {
            $this->factory->verificationService()->issue($user);
        }

        return $this->render('auth/verify-notice', 'Verifikasi Email', [
            'errors' => [],
            'old' => ['email' => $email],
            'flash' => ['type' => 'success', 'message' => 'Jika akun membutuhkan verifikasi, email verifikasi baru telah dikirim.'],
        ]);
    }
}
