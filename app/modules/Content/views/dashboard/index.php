<?php
$auth = $auth ?? [];
$creator = $creator ?? null;
$result = $result ?? ['items' => [], 'total' => 0];
$flash = $flash ?? null;
require base_path('app/modules/Content/views/partials/flash.php');
?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <span class="badge text-bg-dark mb-3">Content</span>
                <h1 class="h4 mb-3">Dashboard Content</h1>
                <p class="text-secondary">Semua karya pada DAYA Platform dipusatkan pada entitas Content. Dari area ini Creator dapat mengelola karya dan mengajukannya ke moderasi.</p>
                <?php if (is_array($creator)): ?>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Creator</div><div class="status-value"><?= e((string) ($creator['display_name'] ?? '-')) ?></div></div></div>
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Creator Code</div><div class="status-value"><?= e((string) ($creator['creator_code'] ?? '-')) ?></div></div></div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap mt-4">
                        <a class="btn btn-dark" href="/content/create">Buat Content</a>
                        <a class="btn btn-outline-secondary" href="/contents">Lihat Directory Publik</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">Anda harus memiliki profil Creator sebelum membuat Content.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Ringkasan</h2>
                <div class="small text-secondary mb-3"><?= e((string) ($auth['email'] ?? '-')) ?></div>
                <div class="status-card">
                    <div class="status-label">Jumlah Content</div>
                    <div class="status-value"><?= e((string) ($result['total'] ?? 0)) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body p-4">
        <h2 class="h5 mb-3">Content Terbaru</h2>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($result['items'] ?? []) as $item): ?>
                        <tr>
                            <td><?= e((string) ($item['content_code'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['title'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['content_type'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['status'] ?? '-')) ?></td>
                            <td class="text-end"><a class="btn btn-sm btn-outline-dark" href="/content/<?= e((string) ($item['id'] ?? 0)) ?>">Kelola</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (($result['items'] ?? []) === []): ?>
                        <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada content.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
