<?php
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
                <span class="badge text-bg-dark mb-3">Collection</span>
                <h1 class="h3 mb-2"><?= e((string) ($collection['title'] ?? '-')) ?></h1>
                <div class="text-secondary mb-3"><?= e((string) ($collection['collection_code'] ?? '-')) ?> | <?= e((string) ($collection['creator_display_name'] ?? '-')) ?></div>
                <?php if (($collection['cover_image_url'] ?? '') !== ''): ?>
                    <div class="mb-3">
                        <img src="<?= e((string) $collection['cover_image_url']) ?>" alt="<?= e((string) ($collection['title'] ?? 'Collection')) ?>" class="img-fluid rounded border">
                    </div>
                <?php endif; ?>
                <p class="mb-0"><?= e((string) ($collection['description'] ?? '')) ?></p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Daftar Content</h2>
                <?php if ($items === []): ?>
                    <div class="text-secondary">Collection ini belum memiliki content publik.</div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($items as $item): ?>
                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                                        <div>
                                            <div class="fw-semibold"><?= e((string) ($item['content_title'] ?? '-')) ?></div>
                                            <div class="small text-secondary"><?= e((string) ($item['content_code'] ?? '-')) ?> | Urutan <?= e((string) ($item['sort_order'] ?? 0)) ?></div>
                                        </div>
                                        <?php if (($item['public_url'] ?? null) !== null): ?>
                                            <a class="btn btn-sm btn-outline-dark" href="<?= e((string) $item['public_url']) ?>">Lihat Content</a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="small text-secondary mt-2"><?= e((string) ($item['content_excerpt'] ?? '')) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
