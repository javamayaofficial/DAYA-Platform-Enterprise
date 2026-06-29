<?php
$formAction = $formAction ?? '#';
$submitLabel = $submitLabel ?? 'Save';
$collection = $collection ?? [];
$errors = $errors ?? [];
$visibilityOptions = $visibilityOptions ?? [];
?>
<form method="post" action="<?= e((string) $formAction) ?>" class="row g-3">
    <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">
    <div class="col-12">
        <label class="form-label">Title</label>
        <input class="form-control<?= isset($errors['title']) ? ' is-invalid' : '' ?>" name="title" value="<?= e((string) ($collection['title'] ?? '')) ?>" placeholder="Nama collection">
        <?php if (isset($errors['title'])): ?><div class="invalid-feedback"><?= e((string) $errors['title']) ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input class="form-control<?= isset($errors['slug']) ? ' is-invalid' : '' ?>" name="slug" value="<?= e((string) ($collection['slug'] ?? '')) ?>" placeholder="collection-slug">
        <?php if (isset($errors['slug'])): ?><div class="invalid-feedback"><?= e((string) $errors['slug']) ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
        <label class="form-label">Visibility</label>
        <select class="form-select<?= isset($errors['visibility']) ? ' is-invalid' : '' ?>" name="visibility">
            <?php foreach ($visibilityOptions as $visibilityKey => $visibilityLabel): ?>
                <option value="<?= e((string) $visibilityKey) ?>"<?= (string) ($collection['visibility'] ?? '') === (string) $visibilityKey ? ' selected' : '' ?>>
                    <?= e((string) $visibilityLabel) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['visibility'])): ?><div class="invalid-feedback"><?= e((string) $errors['visibility']) ?></div><?php endif; ?>
    </div>
    <div class="col-12">
        <label class="form-label">Cover Image URL</label>
        <input class="form-control<?= isset($errors['cover_image_url']) ? ' is-invalid' : '' ?>" name="cover_image_url" value="<?= e((string) ($collection['cover_image_url'] ?? '')) ?>" placeholder="https://example.com/cover.jpg">
        <?php if (isset($errors['cover_image_url'])): ?><div class="invalid-feedback"><?= e((string) $errors['cover_image_url']) ?></div><?php endif; ?>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="5" placeholder="Deskripsi singkat collection"><?= e((string) ($collection['description'] ?? '')) ?></textarea>
    </div>
    <div class="col-12 d-flex gap-2 flex-wrap">
        <button class="btn btn-dark" type="submit"><?= e((string) $submitLabel) ?></button>
        <a class="btn btn-outline-secondary" href="/collection">Kembali</a>
    </div>
</form>
