<?php
$result = $result ?? ['items' => [], 'total' => 0, 'page' => 1, 'last_page' => 1];
$search = $search ?? '';
$category = $category ?? '';
$categories = $categories ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Creator/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Public</span>
                <h1 class="h4 mb-0">Directory Creator</h1>
            </div>
            <div class="small text-secondary">Total aktif: <?= e((string) ($result['total'] ?? 0)) ?></div>
        </div>

        <form method="get" action="/creators" class="row g-3 mb-4">
            <div class="col-md-8">
                <input class="form-control" name="search" value="<?= e((string) $search) ?>" placeholder="Cari creator, handle, atau kategori">
            </div>
            <div class="col-md-4">
                <select class="form-select" name="category">
                    <option value="">Semua kategori</option>
                    <?php foreach ($categories as $categoryLabel): ?>
                        <option value="<?= e((string) $categoryLabel) ?>"<?= (string) $category === (string) $categoryLabel ? ' selected' : '' ?>><?= e((string) $categoryLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button class="btn btn-dark" type="submit">Cari</button>
                <a class="btn btn-outline-secondary" href="/creators">Reset</a>
            </div>
        </form>

        <div class="row g-3">
            <?php foreach (($result['items'] ?? []) as $item): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <div class="small text-secondary mb-2"><?= e((string) ($item['category'] ?? '-')) ?></div>
                            <h2 class="h5 mb-1"><?= e((string) ($item['display_name'] ?? '-')) ?></h2>
                            <div class="small text-secondary mb-1"><?= e((string) ($item['creator_code'] ?? '-')) ?> | @<?= e((string) ($item['slug'] ?? '-')) ?></div>
                            <div class="small text-secondary mb-3"><?= e((string) ($item['creator_type'] ?? '-')) ?> | <?= e((string) ($item['verification_status'] ?? '-')) ?></div>
                            <div class="small text-secondary mb-3">Views: <?= e((string) ($item['profile_view_count'] ?? 0)) ?></div>
                            <a class="btn btn-sm btn-dark" href="/creators/<?= e((string) ($item['slug'] ?? '')) ?>">Lihat Profil</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (($result['items'] ?? []) === []): ?>
                <div class="col-12">
                    <div class="alert alert-secondary mb-0">Belum ada creator aktif yang cocok dengan filter.</div>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <?php
            $pagination = $result;
            $paginationBasePath = '/creators';
            $paginationQuery = [
                'search' => $search,
                'category' => $category,
            ];
            require base_path('app/modules/Creator/views/partials/pagination.php');
            ?>
        </div>
    </div>
</div>
