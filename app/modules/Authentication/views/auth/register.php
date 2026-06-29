<?php $flash = $flash ?? null; $errors = $errors ?? []; $old = $old ?? []; ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <span class="badge text-bg-dark mb-3">Register</span>
                <h1 class="h4 mb-3">Buat akun DAYA Platform</h1>
                <?php if (is_array($flash)): ?>
                    <div class="alert alert-<?= e((string) $flash['type']) ?>"><?= e((string) $flash['message']) ?></div>
                <?php endif; ?>
                <form method="post" action="/auth/register" class="row g-3">
                    <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">
                    <div class="col-12">
                        <label class="form-label">Nama Lengkap</label>
                        <input class="form-control" name="name" value="<?= e((string) ($old['name'] ?? '')) ?>">
                        <?php if (isset($errors['name'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['name']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= e((string) ($old['email'] ?? '')) ?>">
                        <?php if (isset($errors['email'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['email']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password">
                        <?php if (isset($errors['password'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['password']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirmation">
                        <?php if (isset($errors['password_confirmation'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['password_confirmation']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-12 d-flex gap-2 flex-wrap">
                        <button class="btn btn-dark" type="submit">Daftar</button>
                        <a class="btn btn-outline-secondary" href="/auth/login">Sudah punya akun</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
