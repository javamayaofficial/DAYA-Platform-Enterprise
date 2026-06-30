<?php
$auth = $auth ?? [];
$content = $content ?? [];
$statistics = $statistics ?? [];
$reviewStatuses = $reviewStatuses ?? [];
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
                        <span class="badge text-bg-dark mb-2">Admin</span>
                        <h1 class="h4 mb-0"><?= e((string) ($content['title'] ?? '-')) ?></h1>
                    </div>
                    <a class="btn btn-outline-secondary" href="/content/admin">Kembali</a>
                </div>
                <div class="row g-3">
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Content Code</div><div class="status-value"><?= e((string) ($content['content_code'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Creator</div><div class="status-value"><?= e((string) ($content['creator_display_name'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Type</div><div class="status-value"><?= e((string) ($content['content_type'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Status</div><div class="status-value"><?= e((string) ($content['status'] ?? '-')) ?></div></div></div>
                </div>
                <div class="mt-4">
                    <div class="small text-secondary mb-2">Excerpt</div>
                    <p><?= e((string) ($content['excerpt'] ?? '-')) ?></p>
                    <div class="small text-secondary mb-2">Review Notes</div>
                    <p class="mb-0"><?= nl2br(e((string) ($content['review_notes'] ?? '-'))) ?></p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Moderasi Content</h2>
                <form method="post" action="/content/admin/<?= e((string) ($content['id'] ?? 0)) ?>/review" class="row g-3">
                    <?= csrf_input() ?>
                    <div class="col-md-4">
                        <select class="form-select" name="status">
                            <?php foreach ($reviewStatuses as $statusKey => $statusLabel): ?>
                                <option value="<?= e((string) $statusKey) ?>"<?= (string) ($content['status'] ?? '') === (string) $statusKey ? ' selected' : '' ?>><?= e((string) $statusLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" name="review_notes" value="<?= e((string) ($content['review_notes'] ?? '')) ?>" placeholder="Catatan review admin">
                    </div>
                    <div class="col-12 d-grid d-md-flex">
                        <button class="btn btn-dark" type="submit">Simpan Moderasi</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Content Parts</h2>
                <ul class="list-group">
                    <?php foreach ($parts as $part): ?>
                        <li class="list-group-item">
                            <div class="fw-semibold"><?= e((string) ($part['title'] ?? '-')) ?></div>
                            <div class="small text-secondary"><?= e((string) ($part['summary'] ?? '')) ?></div>
                        </li>
                    <?php endforeach; ?>
                    <?php if ($parts === []): ?><li class="list-group-item text-secondary">Belum ada part.</li><?php endif; ?>
                </ul>
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
