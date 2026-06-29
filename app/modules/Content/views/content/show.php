<?php
$content = $content ?? [];
$statistics = $statistics ?? [];
$contentTypes = $contentTypes ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Content/views/partials/flash.php');
$parts = is_array($content['parts'] ?? null) ? $content['parts'] : [];
?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
                    <div>
                        <span class="badge text-bg-dark mb-2">Content</span>
                        <h1 class="h4 mb-0"><?= e((string) ($content['title'] ?? '-')) ?></h1>
                        <div class="small text-secondary mt-1"><?= e((string) ($content['content_code'] ?? '-')) ?> | <?= e((string) ($content['content_type'] ?? '-')) ?> | <?= e((string) ($content['status'] ?? '-')) ?></div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="btn btn-dark" href="/content/<?= e((string) ($content['id'] ?? 0)) ?>/edit">Edit</a>
                        <?php if (($content['status'] ?? '') === 'published' && ($content['visibility'] ?? '') === 'public'): ?>
                            <a class="btn btn-outline-dark" href="<?= e((string) ($content['public_url'] ?? '#')) ?>" target="_blank" rel="noopener">Halaman Publik</a>
                        <?php endif; ?>
                    </div>
                </div>

                <p class="text-secondary mb-3"><?= e((string) ($content['excerpt'] ?? '')) ?></p>
                <div class="small text-secondary mb-3">Slug: <?= e((string) ($content['slug'] ?? '-')) ?> | Access: <?= e((string) ($content['access_policy'] ?? '-')) ?> | Price: <?= e((string) ($content['price_minor'] ?? 0)) ?> <?= e((string) ($content['currency_code'] ?? 'IDR')) ?></div>
                <div class="border rounded p-3 bg-light"><?= nl2br(e((string) ($content['body'] ?? ''))) ?></div>

                <div class="d-flex gap-2 flex-wrap mt-4">
                    <?php if (in_array((string) ($content['status'] ?? ''), ['draft', 'rejected', 'updated'], true)): ?>
                        <form method="post" action="/content/<?= e((string) ($content['id'] ?? 0)) ?>/submit">
                            <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">
                            <button class="btn btn-outline-dark" type="submit">Submit Review</button>
                        </form>
                    <?php endif; ?>
                    <form method="post" action="/content/<?= e((string) ($content['id'] ?? 0)) ?>/delete" onsubmit="return confirm('Hapus content ini?');">
                        <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">
                        <button class="btn btn-outline-danger" type="submit">Soft Delete</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Content Parts</h2>
                <form method="post" action="/content/<?= e((string) ($content['id'] ?? 0)) ?>/parts" class="row g-3 mb-4">
                    <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">
                    <div class="col-md-6"><input class="form-control" name="part_title" placeholder="Judul bagian"></div>
                    <div class="col-md-6"><input class="form-control" name="part_media_url" placeholder="Media URL"></div>
                    <div class="col-12"><textarea class="form-control" name="part_summary" rows="2" placeholder="Ringkasan bagian"></textarea></div>
                    <div class="col-12"><textarea class="form-control" name="part_body" rows="4" placeholder="Isi bagian"></textarea></div>
                    <div class="col-md-4"><input class="form-control" type="date" name="part_release_at"></div>
                    <div class="col-md-4">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="part_is_free_preview" value="1" id="partPreview">
                            <label class="form-check-label" for="partPreview">Free Preview</label>
                        </div>
                    </div>
                    <div class="col-md-4 d-grid"><button class="btn btn-dark" type="submit">Tambah Part</button></div>
                </form>

                <?php if ($parts === []): ?>
                    <div class="text-secondary">Belum ada part.</div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($parts as $part): ?>
                            <div class="col-12">
                                <div class="border rounded p-3 d-flex justify-content-between gap-3 flex-wrap">
                                    <div>
                                        <div class="fw-semibold"><?= e((string) ($part['title'] ?? '-')) ?><?php if (!empty($part['is_free_preview'])): ?> <span class="badge text-bg-warning">Preview</span><?php endif; ?></div>
                                        <div class="small text-secondary mb-2"><?= e((string) ($part['summary'] ?? '')) ?></div>
                                    </div>
                                    <form method="post" action="/content/<?= e((string) ($content['id'] ?? 0)) ?>/parts/<?= e((string) ($part['id'] ?? 0)) ?>/delete">
                                        <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
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
                    <div class="col-12"><div class="status-card"><div class="status-label">Views</div><div class="status-value"><?= e((string) ($statistics['views_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Likes / Comments</div><div class="status-value"><?= e((string) ($statistics['likes_count'] ?? 0)) ?> / <?= e((string) ($statistics['comments_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Shares</div><div class="status-value"><?= e((string) ($statistics['shares_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Revenue Snapshot</div><div class="status-value"><?= e((string) ($statistics['revenue_minor'] ?? 0)) ?></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
