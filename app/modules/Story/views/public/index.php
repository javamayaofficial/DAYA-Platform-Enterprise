<?php
$result = $result ?? ['items' => [], 'total' => 0, 'page' => 1, 'last_page' => 1];
$search = $search ?? '';
$flash = $flash ?? null;
require base_path('app/modules/Story/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Story</span>
                <h1 class="h4 mb-0">Story Directory</h1>
            </div>
            <div class="small text-secondary">Published stories only</div>
        </div>

        <form method="get" action="/stories" class="row g-3 mb-4">
            <div class="col-md-8"><input class="form-control" name="search" value="<?= e((string) $search) ?>" placeholder="Cari title, slug, summary"></div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-dark flex-fill" type="submit">Cari</button>
                <a class="btn btn-outline-secondary flex-fill" href="/stories">Reset</a>
            </div>
        </form>

        <div class="row g-4">
            <?php foreach (($result['items'] ?? []) as $item): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="small text-secondary mb-2"><?= e((string) ($item['story_code'] ?? '-')) ?></div>
                            <h2 class="h5 mb-2"><?= e((string) ($item['title'] ?? '-')) ?></h2>
                            <?php if (($item['subtitle'] ?? '') !== ''): ?>
                                <div class="small text-secondary mb-2"><?= e((string) ($item['subtitle'] ?? '')) ?></div>
                            <?php endif; ?>
                            <p class="text-secondary small mb-3"><?= e((string) ($item['summary'] ?? '')) ?></p>
                            <div class="small text-secondary mb-3"><?= e((string) ($item['creator_display_name'] ?? '-')) ?> | <?= e((string) ($item['reading_time'] ?? 0)) ?> min</div>
                            <a class="btn btn-outline-dark btn-sm" href="<?= e((string) ($item['public_url'] ?? '#')) ?>">Baca Story</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (($result['items'] ?? []) === []): ?>
                <div class="col-12"><div class="text-center text-secondary py-4">Belum ada story publik.</div></div>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <?php
            $pagination = $result;
            $paginationBasePath = '/stories';
            $paginationQuery = ['search' => $search];
            require base_path('app/modules/Story/views/partials/pagination.php');
            ?>
        </div>
    </div>
</div>
