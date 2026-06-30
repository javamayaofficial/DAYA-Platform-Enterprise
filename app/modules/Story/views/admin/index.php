<?php
$auth = $auth ?? [];
$result = $result ?? ['items' => [], 'total' => 0, 'page' => 1, 'last_page' => 1];
$search = $search ?? '';
$status = $status ?? '';
$visibility = $visibility ?? '';
$includeDeleted = $includeDeleted ?? false;
$statuses = $statuses ?? [];
$visibilityOptions = $visibilityOptions ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Story/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Admin</span>
                <h1 class="h4 mb-0">Story Directory</h1>
            </div>
            <div class="small text-secondary"><?= e((string) ($auth['email'] ?? '-')) ?></div>
        </div>

        <form method="get" action="/story/admin" class="row g-3 mb-4">
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
                <select class="form-select" name="visibility">
                    <option value="">Semua visibility</option>
                    <?php foreach ($visibilityOptions as $visibilityKey => $visibilityLabel): ?>
                        <option value="<?= e((string) $visibilityKey) ?>"<?= (string) $visibility === (string) $visibilityKey ? ' selected' : '' ?>><?= e((string) $visibilityLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="include_deleted" value="1" id="includeDeletedStory"<?= !empty($includeDeleted) ? ' checked' : '' ?>>
                    <label class="form-check-label" for="includeDeletedStory">Deleted</label>
                </div>
            </div>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button class="btn btn-dark" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="/story/admin">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Creator</th>
                        <th>Status</th>
                        <th>Visibility</th>
                        <th>Published At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($result['items'] ?? []) as $item): ?>
                        <tr>
                            <td><?= e((string) ($item['story_code'] ?? '-')) ?></td>
                            <td>
                                <div class="fw-semibold"><?= e((string) ($item['title'] ?? '-')) ?></div>
                                <div class="small text-secondary">@<?= e((string) ($item['slug'] ?? '-')) ?></div>
                            </td>
                            <td><?= e((string) ($item['creator_display_name'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['status'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['visibility'] ?? '-')) ?></td>
                            <td><?= e((string) ($item['published_at'] ?? '-')) ?></td>
                            <td class="text-end"><a class="btn btn-sm btn-outline-dark" href="/story/admin/<?= e((string) ($item['id'] ?? 0)) ?>">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (($result['items'] ?? []) === []): ?>
                        <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada story.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <?php
            $pagination = $result;
            $paginationBasePath = '/story/admin';
            $paginationQuery = [
                'search' => $search,
                'status' => $status,
                'visibility' => $visibility,
                'include_deleted' => !empty($includeDeleted) ? '1' : '0',
            ];
            require base_path('app/modules/Story/views/partials/pagination.php');
            ?>
        </div>
    </div>
</div>
