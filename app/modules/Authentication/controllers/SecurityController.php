<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Modules\Authentication\Responses\AuthResponse;

final class SecurityController extends AbstractAuthenticationController
{
    public function dashboard(Request $request): Response
    {
        return $this->render('security/dashboard', 'Security Dashboard', [
            'auth' => $request->session()->get('auth', []),
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }

    public function deviceSessions(Request $request): Response
    {
        $auth = $request->session()->get('auth', []);

        return $this->render('security/device-sessions', 'Device Sessions', [
            'sessions' => $this->factory->deviceSessionService()->listForUser((int) $auth['user_id']),
            'auth' => $auth,
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }

    public function revokeDeviceSession(Request $request): Response
    {
        $auth = $request->session()->get('auth', []);
        $deviceSessionId = (int) $request->input('device_session_id', 0);
        if ($deviceSessionId > 0) {
            $this->factory->deviceSessionService()->revokeForUser((int) $auth['user_id'], $deviceSessionId);
        }
        $request->session()->flash('auth.status', ['type' => 'success', 'message' => 'Device session berhasil dicabut.']);

        return AuthResponse::redirect('/auth/security/sessions');
    }

    public function loginHistory(Request $request): Response
    {
        $auth = $request->session()->get('auth', []);

        return $this->render('security/login-history', 'Login History', [
            'history' => $this->factory->loginHistoryRepository()->recentByUser((int) $auth['user_id']),
            'flash' => $request->session()->pullFlash('auth.status'),
        ]);
    }
}
