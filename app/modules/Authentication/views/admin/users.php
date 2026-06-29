<?php $users = $users ?? []; $flash = $flash ?? null; ?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Admin</span>
                <h1 class="h4 mb-0">Manajemen User</h1>
            </div>
            <a class="btn btn-outline-secondary" href="/auth/security">Kembali</a>
        </div>
        <?php if (is_array($flash)): ?><div class="alert alert-<?= e((string) $flash['type']) ?>"><?= e((string) $flash['message']) ?></div><?php endif; ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Roles</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= e((string) $user['name']) ?></td>
                            <td><?= e((string) $user['email']) ?></td>
                            <td><?= e((string) $user['status']) ?></td>
                            <td><?= e((string) ($user['role_names'] ?? '-')) ?></td>
                            <td class="text-end"><a class="btn btn-sm btn-outline-dark" href="/auth/admin/users/<?= e((string) $user['id']) ?>/roles">Atur Role</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
