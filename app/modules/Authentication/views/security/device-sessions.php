<?php $sessions = $sessions ?? []; $flash = $flash ?? null; ?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Device Sessions</span>
                <h1 class="h4 mb-0">Sesi perangkat aktif</h1>
            </div>
            <a class="btn btn-outline-secondary" href="/auth/security">Kembali</a>
        </div>
        <?php if (is_array($flash)): ?><div class="alert alert-<?= e((string) $flash['type']) ?>"><?= e((string) $flash['message']) ?></div><?php endif; ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Perangkat</th>
                        <th>IP</th>
                        <th>Aktivitas</th>
                        <th>Remember</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $session): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= e((string) $session['device_label']) ?></div>
                                <div class="small text-secondary"><?= e((string) ($session['user_agent'] ?? '')) ?></div>
                            </td>
                            <td><?= e((string) $session['ip_address']) ?></td>
                            <td><?= e((string) $session['last_activity_at']) ?></td>
                            <td><?= ((int) $session['remember_me'] === 1) ? 'Yes' : 'No' ?></td>
                            <td class="text-end">
                                <?php if ((int) $session['is_current'] === 1): ?>
                                    <span class="badge text-bg-success">Current</span>
                                <?php else: ?>
                                    <form method="post" action="/auth/security/sessions/revoke" class="d-inline">
                                        <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">
                                        <input type="hidden" name="device_session_id" value="<?= e((string) $session['id']) ?>">
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Revoke</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
