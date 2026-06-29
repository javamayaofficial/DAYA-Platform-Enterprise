<?php

declare(strict_types=1);

namespace App\Modules\Authentication;

use App\Core\Http\Request;
use App\Core\Logging\Logger;
use App\Core\Modular\BaseModule;
use App\Modules\Authentication\Services\AuthenticationFactory;
use Throwable;

final class AuthenticationModule extends BaseModule
{
    private static ?self $instance = null;

    private function __construct()
    {
        parent::__construct('Authentication', base_path('app/modules/Authentication'));
    }

    public static function instance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function factory(): AuthenticationFactory
    {
        return new AuthenticationFactory($this);
    }

    public function boot(Request $request, Logger $logger): void
    {
        if (!config('app.installed', false)) {
            return;
        }

        try {
            $authService = $this->factory()->authService();
            $rememberCookie = (string) $request->cookie((string) $this->config('remember_cookie_name', 'daya_remember'), '');
            $auth = $request->session()->get('auth', []);

            if ((!is_array($auth) || !isset($auth['user_id'])) && $rememberCookie !== '') {
                $authService->restoreFromRememberCookie($rememberCookie, $request);
            }

            $authService->refreshSessionAuth($request);
        } catch (Throwable $throwable) {
            $logger->warning('Authentication module bootstrap gagal diinisialisasi.', ['message' => $throwable->getMessage()]);
        }
    }
}
