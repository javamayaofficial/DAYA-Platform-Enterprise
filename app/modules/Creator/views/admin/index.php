<?php
$auth = $auth ?? [];
$result = $result ?? ['items' => [], 'total' => 0, 'page' => 1, 'last_page' => 1];
$search = $search ?? '';
$status = $status ?? '';
$category = $category ?? '';
$includeDeleted = $includeDeleted ?? false;
$statuses = $statuses ?? [];
$categories = $categories ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Creator/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Admin</span>
                <h1 class="h4 mb-0">Creator Directory</h1>
            </div>
            <div class="small text-secondary">Login: <?= e((string) ($auth['email'] ?? '-')) ?></div>
        </div>

        <form method="get" action="/creator/admin" class="row g-3 mb-4">
            <div class="col-md-4">
                <input class="form-control" name="search" value="<?= e((string) $search) ?>" placeholder="Cari handle, nama, kategori">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="">Semua status</option>
                    <?php foreach ($statuses as $statusSlug => $statusLabel): ?>
                        <option value="<?= e((string) $statusSlug) ?>"<?= (string) $status === (string) $statusSlug ? ' selected' : '' ?>><?= e((string) $statusLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="category">
                    <option value="">Semua kategori</option>
                    <?php foreach ($categories as $categoryLabel): ?>
                        <option value="<?= e((string) $categoryLabel) ?>"<?= (string) $category === (string) $categoryLabel ? ' selected' : '' ?>><?= e((string) $categoryLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="include_deleted" value="1" id="includeDeleted"<?= !empty($includeDeleted) ? ' checked' : '' ?>>
                    <label class="form-check-label" for="includeDeleted">Deleted</label>
                </div>
            </div>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button class="btn btn-dark" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="/creator/admin">Reset</a>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div class="small text-secondary">Total creator: <?= e((string) ($result['total'] ?? 0)) ?></div>
            <a class="btn btn-sm btn-outline-dark" href="/creator">Dashboard Creator</a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                            <th>Code</th>
                        <th>Creator</th>
                            <th>Type</th>
                        <th>Kategori</th>
                        <th>Status</th>
                            <th>Verification</th>
                        <th>KYC</th>
                        <th>Public</th>
                        <th>Views</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($result['items'] ?? []) as $item): ?>
                        <tr>
                            <td><?= e((string) ($item['creator_code'] ?? '-')) ?></td>
                            <td>
                                <div class="fw-semibold"><?= e((string) ($item['display_name'] ?? '-')) ?></div>
                                <div class="small text-secondary">@<?= e((string) ($item['slug'] ?? '-')) ?> | <?= e((string) ($item['user_email'] ?? '-')) ?></div>
                            </td>
                            <td><?= e((string) ($item['creator_type'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['category'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['status'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['verification_status'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['kyc_status'] ?? '-')) ?></td>
                            <td><?= !empty($item['public_page_enabled']) ? 'Yes' : 'No' ?></td>
                            <td><?= e((string) ($item['profile_view_count'] ?? 0)) ?></td>
                            <td class="text-end"><a class="btn btn-sm btn-outline-dark" href="/creator/admin/<?= e((string) ($item['id'] ?? 0)) ?>">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (($result['items'] ?? []) === []): ?>
                        <tr>
                            <td colspan="10" class="text-center text-secondary py-4">Belum ada data creator.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <?php
            $pagination = $result;
            $paginationBasePath = '/creator/admin';
            $paginationQuery = [
                'search' => $search,
                'status' => $status,
                'category' => $category,
                'include_deleted' => !empty($includeDeleted) ? '1' : '0',
            ];
            require base_path('app/modules/Creator/views/partials/pagination.php');
            ?>
        </div>
    </div>
</div>
