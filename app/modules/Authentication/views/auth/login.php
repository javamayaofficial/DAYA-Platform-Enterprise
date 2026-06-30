<?php $flash = $flash ?? null; $errors = $errors ?? []; $old = $old ?? []; ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-7 col-xl-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <span class="badge text-bg-dark mb-3">Login</span>
                <h1 class="h4 mb-3">Masuk ke akun Anda</h1>
                <?php if (is_array($flash)): ?>
                    <div class="alert alert-<?= e((string) $flash['type']) ?>"><?= e((string) $flash['message']) ?></div>
                <?php endif; ?>
                <form method="post" action="/auth/login" class="row g-3">
                    <?= csrf_input() ?>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= e((string) ($old['email'] ?? '')) ?>">
                        <?php if (isset($errors['email'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['email']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password">
                        <?php if (isset($errors['password'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['password']) ?></div><?php endif; ?>
                    </div>
                    <div class="col-12 form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember_me" <?= ((string) ($old['remember_me'] ?? '0') === '1') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="remember_me">Remember Me</label>
                    </div>
                    <div class="col-12 d-flex gap-2 flex-wrap">
                        <button class="btn btn-dark" type="submit">Login</button>
                        <a class="btn btn-outline-secondary" href="/auth/forgot-password">Lupa Password</a>
                        <a class="btn btn-outline-secondary" href="/auth/register">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
