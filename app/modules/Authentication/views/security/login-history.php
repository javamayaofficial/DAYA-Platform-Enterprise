<?php $history = $history ?? []; $flash = $flash ?? null; ?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Login History</span>
                <h1 class="h4 mb-0">Riwayat login akun</h1>
            </div>
            <a class="btn btn-outline-secondary" href="/auth/security">Kembali</a>
        </div>
        <?php if (is_array($flash)): ?><div class="alert alert-<?= e((string) $flash['type']) ?>"><?= e((string) $flash['message']) ?></div><?php endif; ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>IP</th>
                        <th>Email</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $item): ?>
                        <tr>
                            <td><?= e((string) $item['created_at']) ?></td>
                            <td><?= e((string) $item['status']) ?></td>
                            <td><?= e((string) $item['ip_address']) ?></td>
                            <td><?= e((string) $item['attempted_email']) ?></td>
                            <td><?= e((string) ($item['failure_reason'] ?? '-')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
