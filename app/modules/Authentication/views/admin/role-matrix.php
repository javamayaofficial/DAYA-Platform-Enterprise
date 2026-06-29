<?php $matrix = $matrix ?? []; $flash = $flash ?? null; ?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">RBAC</span>
                <h1 class="h4 mb-0">Matriks Role & Permission</h1>
            </div>
            <a class="btn btn-outline-secondary" href="/auth/security">Kembali</a>
        </div>
        <?php if (is_array($flash)): ?><div class="alert alert-<?= e((string) $flash['type']) ?>"><?= e((string) $flash['message']) ?></div><?php endif; ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Permission</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matrix as $item): ?>
                        <tr>
                            <td><?= e((string) $item['role_name']) ?></td>
                            <td><?= e((string) ($item['permission_name'] ?? '-')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
