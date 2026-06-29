<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

final class AuthMailService
{
    public function sendVerification(string $email, string $name, string $verificationUrl): void
    {
        $subject = 'Verifikasi Email DAYA Platform';
        $body = "Halo {$name},\n\nSilakan verifikasi email Anda melalui tautan berikut:\n{$verificationUrl}\n\nTautan ini memiliki masa berlaku terbatas.";
        $this->dispatch($email, $subject, $body);
    }

    public function sendPasswordReset(string $email, string $name, string $resetUrl): void
    {
        $subject = 'Reset Password DAYA Platform';
        $body = "Halo {$name},\n\nGunakan tautan berikut untuk mengatur ulang password Anda:\n{$resetUrl}\n\nJika Anda tidak meminta reset password, abaikan email ini.";
        $this->dispatch($email, $subject, $body);
    }

    private function dispatch(string $email, string $subject, string $body): void
    {
        $line = '[' . date('Y-m-d H:i:s') . '] TO=' . $email . ' | SUBJECT=' . $subject . PHP_EOL . $body . PHP_EOL . str_repeat('-', 80) . PHP_EOL;
        file_put_contents(storage_path('logs/auth-mail.log'), $line, FILE_APPEND | LOCK_EX);

        if ((string) config('authentication.mail_mode', 'log') === 'mail') {
            @mail($email, $subject, $body, 'From: ' . (string) config('authentication.mail_from', 'no-reply@daya.local'));
        }
    }
}
