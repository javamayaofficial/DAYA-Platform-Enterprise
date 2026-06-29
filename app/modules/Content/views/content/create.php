<?php
$errors = $errors ?? [];
$old = $old ?? [];
$contentTypes = $contentTypes ?? [];
$accessPolicies = $accessPolicies ?? [];
$visibilityOptions = $visibilityOptions ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Content/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Content</span>
                <h1 class="h4 mb-0">Buat Content</h1>
            </div>
            <a class="btn btn-outline-secondary" href="/content">Kembali</a>
        </div>

        <form method="post" action="/content/create" class="row g-3">
            <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">
            <div class="col-md-4">
                <label class="form-label">Content Type</label>
                <select class="form-select" name="content_type">
                    <?php foreach ($contentTypes as $typeKey => $typeLabel): ?>
                        <option value="<?= e((string) $typeKey) ?>"<?= (string) ($old['content_type'] ?? '') === (string) $typeKey ? ' selected' : '' ?>><?= e((string) $typeLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Title</label>
                <input class="form-control" name="title" value="<?= e((string) ($old['title'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input class="form-control" name="slug" value="<?= e((string) ($old['slug'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Cover Image URL</label>
                <input class="form-control" name="cover_image_url" value="<?= e((string) ($old['cover_image_url'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Excerpt</label>
                <textarea class="form-control" name="excerpt" rows="2"><?= e((string) ($old['excerpt'] ?? '')) ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Body</label>
                <textarea class="form-control" name="body" rows="8"><?= e((string) ($old['body'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Access Policy</label>
                <select class="form-select" name="access_policy">
                    <?php foreach ($accessPolicies as $policyKey => $policyLabel): ?>
                        <option value="<?= e((string) $policyKey) ?>"<?= (string) ($old['access_policy'] ?? '') === (string) $policyKey ? ' selected' : '' ?>><?= e((string) $policyLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Price Minor</label>
                <input class="form-control" type="number" min="0" name="price_minor" value="<?= e((string) ($old['price_minor'] ?? 0)) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Visibility</label>
                <select class="form-select" name="visibility">
                    <?php foreach ($visibilityOptions as $visibilityKey => $visibilityLabel): ?>
                        <option value="<?= e((string) $visibilityKey) ?>"<?= (string) ($old['visibility'] ?? '') === (string) $visibilityKey ? ' selected' : '' ?>><?= e((string) $visibilityLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">SEO Title</label>
                <input class="form-control" name="seo_title" value="<?= e((string) ($old['seo_title'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">SEO Description</label>
                <input class="form-control" name="seo_description" value="<?= e((string) ($old['seo_description'] ?? '')) ?>">
            </div>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button class="btn btn-dark" type="submit">Simpan Content</button>
                <a class="btn btn-outline-secondary" href="/content">Batal</a>
            </div>
        </form>
    </div>
</div>
