<?php
$content = $content ?? [];
$statistics = $statistics ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Content/views/partials/flash.php');
$parts = is_array($content['parts'] ?? null) ? $content['parts'] : [];
?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <span class="badge text-bg-dark mb-3"><?= e((string) ($content['content_type'] ?? 'content')) ?></span>
                <h1 class="h3 mb-2"><?= e((string) ($content['title'] ?? '-')) ?></h1>
                <div class="text-secondary mb-3"><?= e((string) ($content['content_code'] ?? '-')) ?> | <?= e((string) ($content['creator_display_name'] ?? '-')) ?></div>
                <p class="mb-3"><?= e((string) ($content['excerpt'] ?? '')) ?></p>
                <div class="border rounded p-3 bg-light"><?= nl2br(e((string) ($content['body'] ?? ''))) ?></div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Content Parts</h2>
                <?php if ($parts === []): ?>
                    <div class="text-secondary">Content ini belum memiliki part publik.</div>
                <?php else: ?>
                    <div class="accordion" id="contentParts">
                        <?php foreach ($parts as $index => $part): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="partHeading<?= e((string) $index) ?>">
                                    <button class="accordion-button<?= $index === 0 ? '' : ' collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#partCollapse<?= e((string) $index) ?>">
                                        <?= e((string) ($part['title'] ?? '-')) ?>
                                    </button>
                                </h2>
                                <div id="partCollapse<?= e((string) $index) ?>" class="accordion-collapse collapse<?= $index === 0 ? ' show' : '' ?>" data-bs-parent="#contentParts">
                                    <div class="accordion-body">
                                        <div class="small text-secondary mb-2"><?= e((string) ($part['summary'] ?? '')) ?></div>
                                        <div><?= nl2br(e((string) ($part['body'] ?? ''))) ?></div>
                                    </div>
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
                    <div class="col-12"><div class="status-card"><div class="status-label">Recommendation</div><div class="status-value"><?= e((string) ($statistics['recommendation_score'] ?? 0)) ?></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
