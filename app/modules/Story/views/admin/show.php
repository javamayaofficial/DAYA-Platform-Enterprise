<?php
$auth = $auth ?? [];
$story = $story ?? [];
$statistics = $statistics ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Story/views/partials/flash.php');
?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
                    <div>
                        <span class="badge text-bg-dark mb-2">Admin</span>
                        <h1 class="h4 mb-0"><?= e((string) ($story['title'] ?? '-')) ?></h1>
                        <div class="small text-secondary mt-1"><?= e((string) ($story['story_code'] ?? '-')) ?> | <?= e((string) ($story['status'] ?? '-')) ?> | <?= e((string) ($story['visibility'] ?? '-')) ?></div>
                    </div>
                    <a class="btn btn-outline-secondary" href="/story/admin">Kembali</a>
                </div>

                <div class="small text-secondary mb-3"><?= e((string) ($auth['email'] ?? '-')) ?></div>
                <p class="text-secondary mb-3"><?= e((string) ($story['summary'] ?? '')) ?></p>
                <div class="small text-secondary">
                    Creator: <?= e((string) ($story['creator_display_name'] ?? '-')) ?> |
                    Slug: <?= e((string) ($story['slug'] ?? '-')) ?> |
                    Published At: <?= e((string) ($story['published_at'] ?? '-')) ?>
                </div>
                <?php if (!empty($story['collection_id'])): ?>
                    <div class="small text-secondary mt-2">
                        Collection: <?= e((string) ($story['collection_code'] ?? '-')) ?> | <?= e((string) ($story['collection_title'] ?? '-')) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Body (Read Only)</h2>
                <div class="border rounded p-3 bg-light"><?= nl2br(e((string) ($story['body'] ?? ''))) ?></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Statistik</h2>
                <div class="row g-3">
                    <div class="col-12"><div class="status-card"><div class="status-label">Word Count</div><div class="status-value"><?= e((string) ($statistics['word_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Reading Time</div><div class="status-value"><?= e((string) ($statistics['reading_time'] ?? 0)) ?> min</div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Has Collection</div><div class="status-value"><?= !empty($statistics['has_collection']) ? 'Yes' : 'No' ?></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
