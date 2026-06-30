<?php
$story = $story ?? [];
$statistics = $statistics ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Story/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Preview</span>
                <h1 class="h4 mb-0"><?= e((string) ($story['title'] ?? '-')) ?></h1>
                <div class="small text-secondary mt-1"><?= e((string) ($story['story_code'] ?? '-')) ?> | <?= e((string) ($story['status'] ?? '-')) ?></div>
            </div>
            <a class="btn btn-outline-secondary" href="/story/<?= e((string) ($story['id'] ?? 0)) ?>">Kembali</a>
        </div>

        <?php if (($story['cover'] ?? '') !== ''): ?>
            <div class="mb-3">
                <img src="<?= e((string) $story['cover']) ?>" alt="<?= e((string) ($story['title'] ?? 'Story')) ?>" class="img-fluid rounded border">
            </div>
        <?php endif; ?>

        <?php if (($story['subtitle'] ?? '') !== ''): ?>
            <div class="text-secondary mb-2"><?= e((string) ($story['subtitle'] ?? '')) ?></div>
        <?php endif; ?>

        <p class="text-secondary mb-3"><?= e((string) ($story['summary'] ?? '')) ?></p>
        <div class="small text-secondary mb-3">
            <?= e((string) ($statistics['word_count'] ?? 0)) ?> words | <?= e((string) ($statistics['reading_time'] ?? 0)) ?> min read
        </div>
        <div class="border rounded p-3 bg-light"><?= nl2br(e((string) ($story['body'] ?? ''))) ?></div>
    </div>
</div>
