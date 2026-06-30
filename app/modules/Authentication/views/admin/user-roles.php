<?php $user = $user ?? null; $roles = $roles ?? []; $assignedRoles = $assignedRoles ?? []; $flash = $flash ?? null; ?>
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <span class="badge text-bg-dark mb-3">Role Assignment</span>
                <h1 class="h4 mb-1">Atur role user</h1>
                <p class="text-secondary">User: <?= $user ? e((string) $user->email) : '-' ?></p>
                <?php if (is_array($flash)): ?><div class="alert alert-<?= e((string) $flash['type']) ?>"><?= e((string) $flash['message']) ?></div><?php endif; ?>
                <form method="post" action="/auth/admin/users/<?= e((string) ($user?->id ?? 0)) ?>/roles" class="row g-3">
                    <?= csrf_input() ?>
                    <?php foreach ($roles as $role): ?>
                        <div class="col-md-6">
                            <div class="form-check border rounded-3 p-3">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="<?= e((string) $role['slug']) ?>" id="role_<?= e((string) $role['slug']) ?>" <?= in_array((string) $role['slug'], $assignedRoles, true) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="role_<?= e((string) $role['slug']) ?>">
                                    <?= e((string) $role['name']) ?>
                                </label>
                                <div class="small text-secondary"><?= e((string) ($role['description'] ?? '')) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-12 d-flex gap-2 flex-wrap">
                        <button class="btn btn-dark" type="submit">Simpan Role</button>
                        <a class="btn btn-outline-secondary" href="/auth/admin/users">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
