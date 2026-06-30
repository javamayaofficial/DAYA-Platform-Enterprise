<?php $auth = $auth ?? []; $flash = $flash ?? null; ?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <span class="badge text-bg-dark mb-3">Security</span>
                <h1 class="h4 mb-3">Dashboard Keamanan Akun</h1>
                <?php if (is_array($flash)): ?><div class="alert alert-<?= e((string) $flash['type']) ?>"><?= e((string) $flash['message']) ?></div><?php endif; ?>
                <p class="text-secondary">Area ini merangkum status akun, roles, permission, riwayat login, dan device sessions aktif.</p>
                <div class="row g-3 mt-1">
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Nama</div><div class="status-value"><?= e((string) ($auth['name'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Email</div><div class="status-value"><?= e((string) ($auth['email'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Status</div><div class="status-value"><?= e((string) ($auth['status'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Roles</div><div class="status-value"><?= e(implode(', ', $auth['roles'] ?? [])) ?></div></div></div>
                </div>
                <div class="d-flex gap-2 flex-wrap mt-4">
                    <a class="btn btn-dark" href="/auth/security/sessions">Device Sessions</a>
                    <a class="btn btn-outline-dark" href="/auth/security/login-history">Login History</a>
                    <form method="post" action="/auth/logout" class="d-inline">
                        <?= csrf_input() ?>
                        <button class="btn btn-outline-danger" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Permission Aktif</h2>
                <ul class="small ps-3 mb-0">
                    <?php foreach (($auth['permissions'] ?? []) as $permission): ?>
                        <li><?= e((string) $permission) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
