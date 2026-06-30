<?php
$collection = $collection ?? [];
$statistics = $statistics ?? [];
$availableContents = $availableContents ?? [];
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
                        <span class="badge text-bg-dark mb-2">Collection</span>
                        <h1 class="h4 mb-0"><?= e((string) ($collection['title'] ?? '-')) ?></h1>
                        <div class="small text-secondary mt-1"><?= e((string) ($collection['collection_code'] ?? '-')) ?> | <?= e((string) ($collection['status'] ?? '-')) ?> | <?= e((string) ($collection['visibility'] ?? '-')) ?></div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="btn btn-dark" href="/collection/<?= e((string) ($collection['id'] ?? 0)) ?>/edit">Edit</a>
                        <?php if (($collection['status'] ?? '') === 'draft'): ?>
                            <form method="post" action="/collection/<?= e((string) ($collection['id'] ?? 0)) ?>/publish">
                                <?= csrf_input() ?>
                                <button class="btn btn-outline-dark" type="submit">Publish</button>
                            </form>
                        <?php else: ?>
                            <form method="post" action="/collection/<?= e((string) ($collection['id'] ?? 0)) ?>/draft">
                                <?= csrf_input() ?>
                                <button class="btn btn-outline-dark" type="submit">Kembali ke Draft</button>
                            </form>
                        <?php endif; ?>
                        <?php if (($collection['status'] ?? '') === 'published' && ($collection['visibility'] ?? '') === 'public'): ?>
                            <a class="btn btn-outline-secondary" href="<?= e((string) ($collection['public_url'] ?? '#')) ?>" target="_blank" rel="noopener">Halaman Publik</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (($collection['cover_image_url'] ?? '') !== ''): ?>
                    <div class="mb-3">
                        <img src="<?= e((string) $collection['cover_image_url']) ?>" alt="<?= e((string) ($collection['title'] ?? 'Collection')) ?>" class="img-fluid rounded border">
                    </div>
                <?php endif; ?>

                <p class="text-secondary mb-3"><?= e((string) ($collection['description'] ?? '')) ?></p>
                <div class="small text-secondary">Slug: <?= e((string) ($collection['slug'] ?? '-')) ?> | Creator: <?= e((string) ($collection['creator_display_name'] ?? '-')) ?></div>

                <div class="d-flex gap-2 flex-wrap mt-4">
                    <form method="post" action="/collection/<?= e((string) ($collection['id'] ?? 0)) ?>/delete" onsubmit="return confirm('Hapus collection ini?');">
                        <?= csrf_input() ?>
                        <button class="btn btn-outline-danger" type="submit">Soft Delete</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Content Dalam Collection</h2>

                <form method="post" action="/collection/<?= e((string) ($collection['id'] ?? 0)) ?>/items" class="row g-3 mb-4">
                    <?= csrf_input() ?>
                    <div class="col-md-9">
                        <select class="form-select" name="content_id">
                            <option value="">Pilih Content</option>
                            <?php foreach ($availableContents as $content): ?>
                                <option value="<?= e((string) ($content['id'] ?? 0)) ?>">
                                    <?= e((string) ($content['content_code'] ?? '-')) ?> | <?= e((string) ($content['title'] ?? '-')) ?> | <?= e((string) ($content['status'] ?? '-')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button class="btn btn-dark" type="submit">Tambah Content</button>
                    </div>
                </form>

                <?php if ($items === []): ?>
                    <div class="text-secondary">Belum ada content di collection ini.</div>
                <?php else: ?>
                    <form method="post" action="/collection/<?= e((string) ($collection['id'] ?? 0)) ?>/items/reorder">
                        <?= csrf_input() ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 120px;">Urutan</th>
                                        <th>Content</th>
                                        <th>Status</th>
                                        <th style="width: 120px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td>
                                                <input class="form-control" type="number" min="1" name="item_orders[<?= e((string) ($item['id'] ?? 0)) ?>]" value="<?= e((string) ($item['sort_order'] ?? 1)) ?>">
                                            </td>
                                            <td>
                                                <div class="fw-semibold"><?= e((string) ($item['content_title'] ?? '-')) ?></div>
                                                <div class="small text-secondary"><?= e((string) ($item['content_code'] ?? '-')) ?> | @<?= e((string) ($item['content_slug'] ?? '-')) ?></div>
                                                <div class="small text-secondary"><?= e((string) ($item['content_excerpt'] ?? '')) ?></div>
                                            </td>
                                            <td><?= e((string) ($item['content_status'] ?? '-')) ?> / <?= e((string) ($item['content_visibility'] ?? '-')) ?></td>
                                            <td class="text-end">
                                                <button
                                                    class="btn btn-sm btn-outline-danger"
                                                    type="submit"
                                                    formaction="/collection/<?= e((string) ($collection['id'] ?? 0)) ?>/items/<?= e((string) ($item['id'] ?? 0)) ?>/delete"
                                                    formmethod="post"
                                                >
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-outline-dark" type="submit">Simpan Urutan</button>
                        </div>
                    </form>
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
