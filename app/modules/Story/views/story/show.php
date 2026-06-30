<?php
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
                        <span class="badge text-bg-dark mb-2">Story</span>
                        <h1 class="h4 mb-0"><?= e((string) ($story['title'] ?? '-')) ?></h1>
                        <div class="small text-secondary mt-1">
                            <?= e((string) ($story['story_code'] ?? '-')) ?> | <?= e((string) ($story['status'] ?? '-')) ?> | <?= e((string) ($story['visibility'] ?? '-')) ?>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="btn btn-dark" href="/story/<?= e((string) ($story['id'] ?? 0)) ?>/edit">Edit</a>
                        <a class="btn btn-outline-dark" href="/story/<?= e((string) ($story['id'] ?? 0)) ?>/preview">Preview</a>
                        <?php if (($story['status'] ?? '') === 'published' && ($story['visibility'] ?? '') === 'public'): ?>
                            <a class="btn btn-outline-secondary" href="<?= e((string) ($story['public_url'] ?? '#')) ?>" target="_blank" rel="noopener">Halaman Publik</a>
                        <?php endif; ?>
                    </div>
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
                    Slug: <?= e((string) ($story['slug'] ?? '-')) ?> |
                    Language: <?= e((string) ($story['language'] ?? '-')) ?> |
                    Genre: <?= e((string) ($story['genre'] ?? '-')) ?> |
                    Reading: <?= e((string) ($story['reading_time'] ?? 0)) ?> min
                </div>
                <?php if (!empty($story['collection_id'])): ?>
                    <div class="small text-secondary mb-3">
                        Collection: <?= e((string) ($story['collection_code'] ?? '-')) ?> | <?= e((string) ($story['collection_title'] ?? '-')) ?>
                    </div>
                <?php endif; ?>

                <div class="border rounded p-3 bg-light"><?= nl2br(e((string) ($story['body'] ?? ''))) ?></div>

                <div class="d-flex gap-2 flex-wrap mt-4">
                    <form method="post" action="/story/<?= e((string) ($story['id'] ?? 0)) ?>/review">
                        <?= csrf_input() ?>
                        <button class="btn btn-outline-dark" type="submit">Mark Review</button>
                    </form>
                    <form method="post" action="/story/<?= e((string) ($story['id'] ?? 0)) ?>/publish">
                        <?= csrf_input() ?>
                        <button class="btn btn-outline-dark" type="submit">Publish Now</button>
                    </form>
                    <form method="post" action="/story/<?= e((string) ($story['id'] ?? 0)) ?>/schedule" class="d-flex gap-2 flex-wrap">
                        <?= csrf_input() ?>
                        <input class="form-control" type="datetime-local" name="published_at" value="">
                        <button class="btn btn-outline-dark" type="submit">Schedule</button>
                    </form>
                    <form method="post" action="/story/<?= e((string) ($story['id'] ?? 0)) ?>/archive">
                        <?= csrf_input() ?>
                        <button class="btn btn-outline-secondary" type="submit">Archive</button>
                    </form>
                    <form method="post" action="/story/<?= e((string) ($story['id'] ?? 0)) ?>/duplicate">
                        <?= csrf_input() ?>
                        <button class="btn btn-outline-secondary" type="submit">Duplicate</button>
                    </form>
                    <form method="post" action="/story/<?= e((string) ($story['id'] ?? 0)) ?>/delete" onsubmit="return confirm('Hapus story ini?');">
                        <?= csrf_input() ?>
                        <button class="btn btn-outline-danger" type="submit">Soft Delete</button>
                    </form>
                </div>
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
                    <div class="col-12"><div class="status-card"><div class="status-label">Published</div><div class="status-value"><?= !empty($statistics['is_published']) ? 'Yes' : 'No' ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Scheduled</div><div class="status-value"><?= !empty($statistics['is_scheduled']) ? 'Yes' : 'No' ?></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
