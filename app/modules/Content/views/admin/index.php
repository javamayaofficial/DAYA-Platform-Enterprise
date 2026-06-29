<?php
$auth = $auth ?? [];
$result = $result ?? ['items' => [], 'total' => 0, 'page' => 1, 'last_page' => 1];
$search = $search ?? '';
$status = $status ?? '';
$contentType = $contentType ?? '';
$includeDeleted = $includeDeleted ?? false;
$statuses = $statuses ?? [];
$contentTypes = $contentTypes ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Content/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Admin</span>
                <h1 class="h4 mb-0">Content Directory</h1>
            </div>
            <div class="small text-secondary"><?= e((string) ($auth['email'] ?? '-')) ?></div>
        </div>

        <form method="get" action="/content/admin" class="row g-3 mb-4">
            <div class="col-md-4"><input class="form-control" name="search" value="<?= e((string) $search) ?>" placeholder="Cari code, title, slug"></div>
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="">Semua status</option>
                    <?php foreach ($statuses as $statusKey => $statusLabel): ?>
                        <option value="<?= e((string) $statusKey) ?>"<?= (string) $status === (string) $statusKey ? ' selected' : '' ?>><?= e((string) $statusLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="content_type">
                    <option value="">Semua type</option>
                    <?php foreach ($contentTypes as $typeKey => $typeLabel): ?>
                        <option value="<?= e((string) $typeKey) ?>"<?= (string) $contentType === (string) $typeKey ? ' selected' : '' ?>><?= e((string) $typeLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="include_deleted" value="1" id="includeDeletedContent"<?= !empty($includeDeleted) ? ' checked' : '' ?>>
                    <label class="form-check-label" for="includeDeletedContent">Deleted</label>
                </div>
            </div>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button class="btn btn-dark" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="/content/admin">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Creator</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($result['items'] ?? []) as $item): ?>
                        <tr>
                            <td><?= e((string) ($item['content_code'] ?? '-')) ?></td>
                            <td>
                                <div class="fw-semibold"><?= e((string) ($item['title'] ?? '-')) ?></div>
                                <div class="small text-secondary">@<?= e((string) ($item['slug'] ?? '-')) ?></div>
                            </td>
                            <td><?= e((string) ($item['creator_display_name'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['content_type'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['status'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['views_count'] ?? 0)) ?></td>
                            <td class="text-end"><a class="btn btn-sm btn-outline-dark" href="/content/admin/<?= e((string) ($item['id'] ?? 0)) ?>">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (($result['items'] ?? []) === []): ?>
                        <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada content.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <?php
            $pagination = $result;
            $paginationBasePath = '/content/admin';
            $paginationQuery = [
                'search' => $search,
                'status' => $status,
                'content_type' => $contentType,
                'include_deleted' => !empty($includeDeleted) ? '1' : '0',
            ];
            require base_path('app/modules/Content/views/partials/pagination.php');
            ?>
        </div>
    </div>
</div>
