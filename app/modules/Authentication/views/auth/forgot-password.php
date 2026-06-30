<?php $flash = $flash ?? null; $errors = $errors ?? []; $old = $old ?? []; ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-7 col-xl-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <span class="badge text-bg-dark mb-3">Forgot Password</span>
                <h1 class="h4 mb-3">Minta tautan reset password</h1>
                <?php if (is_array($flash)): ?>
                    <div class="alert alert-<?= e((string) $flash['type']) ?>"><?= e((string) $flash['message']) ?></div>
                <?php endif; ?>
                <form method="post" action="/auth/forgot-password" class="row g-3">
                    <?= csrf_input() ?>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= e((string) ($old['email'] ?? '')) ?>">
                        <?php if (isset($errors['email'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['email']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-12 d-flex gap-2 flex-wrap">
                        <button class="btn btn-dark" type="submit">Kirim Tautan</button>
                        <a class="btn btn-outline-secondary" href="/auth/login">Kembali Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
