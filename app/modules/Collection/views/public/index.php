<?php
$result = $result ?? ['items' => [], 'total' => 0, 'page' => 1, 'last_page' => 1];
$status = $status ?? '';
$visibility = $visibility ?? '';
$flash = $flash ?? null;
require base_path('app/modules/Collection/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Collection</span>
                <h1 class="h4 mb-0">Collection Directory</h1>
            </div>
            <div class="small text-secondary">Published collections only</div>
        </div>

        <form method="get" action="/collections" class="row g-3 mb-4">
            <div class="col-md-4">
                <select class="form-select" name="status">
                    <option value="">Status published</option>
                    <option value="published"<?= (string) $status === 'published' ? ' selected' : '' ?>>Published</option>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" name="visibility">
                    <option value="">Visibility public</option>
                    <option value="public"<?= (string) $visibility === 'public' ? ' selected' : '' ?>>Public</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-dark flex-fill" type="submit">Filter</button>
                <a class="btn btn-outline-secondary flex-fill" href="/collections">Reset</a>
            </div>
        </form>

        <div class="row g-4">
            <?php foreach (($result['items'] ?? []) as $item): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="small text-secondary mb-2"><?= e((string) ($item['collection_code'] ?? '-')) ?></div>
                            <h2 class="h5 mb-2"><?= e((string) ($item['title'] ?? '-')) ?></h2>
                            <p class="text-secondary small mb-3"><?= e((string) ($item['description'] ?? '')) ?></p>
                            <div class="small text-secondary mb-3"><?= e((string) ($item['creator_display_name'] ?? '-')) ?> | <?= e((string) ($item['items_count'] ?? 0)) ?> item</div>
                            <a class="btn btn-outline-dark btn-sm" href="<?= e((string) ($item['public_url'] ?? '#')) ?>">Lihat Collection</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (($result['items'] ?? []) === []): ?>
                <div class="col-12"><div class="text-center text-secondary py-4">Belum ada collection publik.</div></div>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <?php
            $pagination = $result;
            $paginationBasePath = '/collections';
            $paginationQuery = [
                'status' => $status,
                'visibility' => $visibility,
            ];
            require base_path('app/modules/Collection/views/partials/pagination.php');
            ?>
        </div>
    </div>
</div>
