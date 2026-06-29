<?php
$auth = $auth ?? [];
$collection = $collection ?? [];
$statistics = $statistics ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Collection/views/partials/flash.php');
$items = is_array($collection['items'] ?? null) ? $collection['items'] : [];
?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
                    <div>
                        <span class="badge text-bg-dark mb-2">Admin</span>
                        <h1 class="h4 mb-0"><?= e((string) ($collection['title'] ?? '-')) ?></h1>
                        <div class="small text-secondary mt-1"><?= e((string) ($collection['collection_code'] ?? '-')) ?> | <?= e((string) ($collection['status'] ?? '-')) ?> | <?= e((string) ($collection['visibility'] ?? '-')) ?></div>
                    </div>
                    <a class="btn btn-outline-secondary" href="/collection/admin">Kembali</a>
                </div>
                <div class="small text-secondary mb-3"><?= e((string) ($auth['email'] ?? '-')) ?></div>
                <p class="text-secondary mb-3"><?= e((string) ($collection['description'] ?? '')) ?></p>
                <div class="small text-secondary">Creator: <?= e((string) ($collection['creator_display_name'] ?? '-')) ?> | Slug: <?= e((string) ($collection['slug'] ?? '-')) ?></div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Collection Items</h2>
                <?php if ($items === []): ?>
                    <div class="text-secondary">Collection ini belum memiliki item aktif.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Urutan</th>
                                    <th>Content</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= e((string) ($item['sort_order'] ?? 0)) ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= e((string) ($item['content_title'] ?? '-')) ?></div>
                                            <div class="small text-secondary"><?= e((string) ($item['content_code'] ?? '-')) ?> | @<?= e((string) ($item['content_slug'] ?? '-')) ?></div>
                                        </td>
                                        <td><?= e((string) ($item['content_status'] ?? '-')) ?> / <?= e((string) ($item['content_visibility'] ?? '-')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Statistik</h2>
                <div class="row g-3">
                    <div class="col-12"><div class="status-card"><div class="status-label">Total Item</div><div class="status-value"><?= e((string) ($statistics['items_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Published Item</div><div class="status-value"><?= e((string) ($statistics['published_items_count'] ?? 0)) ?></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
