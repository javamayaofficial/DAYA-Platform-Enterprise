<?php $result = $result ?? ['success' => false, 'message' => 'Status verifikasi tidak tersedia.']; ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-7 col-xl-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <span class="badge <?= !empty($result['success']) ? 'text-bg-success' : 'text-bg-danger' ?> mb-3">
                    <?= !empty($result['success']) ? 'Verification Success' : 'Verification Failed' ?>
                </span>
                <h1 class="h4 mb-3">Status verifikasi email</h1>
                <p class="text-secondary"><?= e((string) $result['message']) ?></p>
                <div class="d-flex gap-2 flex-wrap">
                    <a class="btn btn-dark" href="/auth/login">Ke Login</a>
                    <a class="btn btn-outline-secondary" href="/auth/verify-notice">Kirim Ulang Verifikasi</a>
                </div>
            </div>
        </div>
    </div>
</div>
