<?php
$result = $result ?? ['items' => [], 'total' => 0, 'page' => 1, 'last_page' => 1];
$search = $search ?? '';
$contentType = $contentType ?? '';
$contentTypes = $contentTypes ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Content/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Public</span>
                <h1 class="h4 mb-0">Content Directory</h1>
            </div>
            <div class="small text-secondary">Total published: <?= e((string) ($result['total'] ?? 0)) ?></div>
        </div>
        <form method="get" action="/contents" class="row g-3 mb-4">
            <div class="col-md-8"><input class="form-control" name="search" value="<?= e((string) $search) ?>" placeholder="Cari title atau slug"></div>
            <div class="col-md-4">
                <select class="form-select" name="content_type">
                    <option value="">Semua type</option>
                    <?php foreach ($contentTypes as $typeKey => $typeLabel): ?>
                        <option value="<?= e((string) $typeKey) ?>"<?= (string) $contentType === (string) $typeKey ? ' selected' : '' ?>><?= e((string) $typeLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button class="btn btn-dark" type="submit">Cari</button>
                <a class="btn btn-outline-secondary" href="/contents">Reset</a>
            </div>
        </form>

        <div class="row g-3">
            <?php foreach (($result['items'] ?? []) as $item): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="small text-secondary mb-2"><?= e((string) ($item['content_type'] ?? '-')) ?> | <?= e((string) ($item['content_code'] ?? '-')) ?></div>
                            <h2 class="h5 mb-1"><?= e((string) ($item['title'] ?? '-')) ?></h2>
                            <div class="small text-secondary mb-2"><?= e((string) ($item['creator_display_name'] ?? '-')) ?></div>
                            <p class="small text-secondary mb-3"><?= e((string) ($item['excerpt'] ?? '')) ?></p>
                            <a class="btn btn-sm btn-dark" href="/contents/<?= e((string) ($item['slug'] ?? '')) ?>">Lihat Content</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (($result['items'] ?? []) === []): ?>
                <div class="col-12"><div class="alert alert-secondary mb-0">Belum ada content published yang cocok.</div></div>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <?php
            $pagination = $result;
            $paginationBasePath = '/contents';
            $paginationQuery = [
                'search' => $search,
                'content_type' => $contentType,
            ];
            require base_path('app/modules/Content/views/partials/pagination.php');
            ?>
        </div>
    </div>
</div>
