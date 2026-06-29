<?php $flash = $flash ?? null; $errors = $errors ?? []; $old = $old ?? []; ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <span class="badge text-bg-dark mb-3">Reset Password</span>
                <h1 class="h4 mb-3">Atur ulang password akun</h1>
                <?php if (is_array($flash)): ?>
                    <div class="alert alert-<?= e((string) $flash['type']) ?>"><?= e((string) $flash['message']) ?></div>
                <?php endif; ?>
                <form method="post" action="/auth/reset-password" class="row g-3">
                    <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">
                    <input type="hidden" name="token" value="<?= e((string) ($old['token'] ?? '')) ?>">
                    <?php if (isset($errors['token'])): ?><div class="col-12"><div class="alert alert-danger mb-0"><?= e((string) $errors['token']) ?></div></div><?php endif; ?>
                    <div class="col-md-6">
                        <label class="form-label">Password Baru</label>
                        <input type="password" class="form-control" name="password">
                        <?php if (isset($errors['password'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['password']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirmation">
                        <?php if (isset($errors['password_confirmation'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['password_confirmation']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-12 d-flex gap-2 flex-wrap">
                        <button class="btn btn-dark" type="submit">Reset Password</button>
                        <a class="btn btn-outline-secondary" href="/auth/login">Kembali Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
