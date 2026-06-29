<?php
$auth = $auth ?? [];
$creator = $creator ?? null;
$result = $result ?? ['items' => [], 'total' => 0];
$flash = $flash ?? null;
require base_path('app/modules/Collection/views/partials/flash.php');
?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <span class="badge text-bg-dark mb-3">Collection</span>
                <h1 class="h4 mb-3">Dashboard Collection</h1>
                <p class="text-secondary">Collection adalah wadah terurut untuk mengelompokkan banyak Content milik Creator yang sama tanpa menyimpan isi karya.</p>
                <?php if (is_array($creator)): ?>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Creator</div><div class="status-value"><?= e((string) ($creator['display_name'] ?? '-')) ?></div></div></div>
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Creator Code</div><div class="status-value"><?= e((string) ($creator['creator_code'] ?? '-')) ?></div></div></div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap mt-4">
                        <a class="btn btn-dark" href="/collection/create">Buat Collection</a>
                        <a class="btn btn-outline-secondary" href="/collections">Lihat Directory Publik</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">Anda harus memiliki profil Creator sebelum membuat collection.</div>
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
                    <div class="status-label">Jumlah Collection</div>
                    <div class="status-value"><?= e((string) ($result['total'] ?? 0)) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body p-4">
        <h2 class="h5 mb-3">Collection Terbaru</h2>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Visibility</th>
                        <th>Status</th>
                        <th>Items</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($result['items'] ?? []) as $item): ?>
                        <tr>
                            <td><?= e((string) ($item['collection_code'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['title'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['visibility'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['status'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['items_count'] ?? 0)) ?></td>
                            <td class="text-end"><a class="btn btn-sm btn-outline-dark" href="/collection/<?= e((string) ($item['id'] ?? 0)) ?>">Kelola</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (($result['items'] ?? []) === []): ?>
                        <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada collection.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
