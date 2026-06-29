<?php
$formAction = $formAction ?? '#';
$submitLabel = $submitLabel ?? 'Save';
$story = $story ?? [];
$errors = $errors ?? [];
$languageOptions = $languageOptions ?? [];
$visibilityOptions = $visibilityOptions ?? [];
$collections = $collections ?? [];
?>
<form method="post" action="<?= e((string) $formAction) ?>" class="row g-3">
    <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">
    <div class="col-md-8">
        <label class="form-label">Title</label>
        <input class="form-control<?= isset($errors['title']) ? ' is-invalid' : '' ?>" name="title" value="<?= e((string) ($story['title'] ?? '')) ?>">
        <?php if (isset($errors['title'])): ?><div class="invalid-feedback"><?= e((string) $errors['title']) ?></div><?php endif; ?>
    </div>
    <div class="col-md-4">
        <label class="form-label">Collection</label>
        <select class="form-select" name="collection_id">
            <option value="">Tanpa Collection</option>
            <?php foreach ($collections as $collection): ?>
                <option value="<?= e((string) ($collection['id'] ?? 0)) ?>"<?= (string) ($story['collection_id'] ?? '') === (string) ($collection['id'] ?? 0) ? ' selected' : '' ?>>
                    <?= e((string) ($collection['collection_code'] ?? '-')) ?> | <?= e((string) ($collection['title'] ?? '-')) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Subtitle</label>
        <input class="form-control" name="subtitle" value="<?= e((string) ($story['subtitle'] ?? '')) ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input class="form-control<?= isset($errors['slug']) ? ' is-invalid' : '' ?>" name="slug" value="<?= e((string) ($story['slug'] ?? '')) ?>">
        <?php if (isset($errors['slug'])): ?><div class="invalid-feedback"><?= e((string) $errors['slug']) ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
        <label class="form-label">Cover URL</label>
        <input class="form-control<?= isset($errors['cover']) ? ' is-invalid' : '' ?>" name="cover" value="<?= e((string) ($story['cover'] ?? '')) ?>">
        <?php if (isset($errors['cover'])): ?><div class="invalid-feedback"><?= e((string) $errors['cover']) ?></div><?php endif; ?>
    </div>
    <div class="col-md-3">
        <label class="form-label">Language</label>
        <select class="form-select<?= isset($errors['language']) ? ' is-invalid' : '' ?>" name="language">
            <?php foreach ($languageOptions as $languageKey => $languageLabel): ?>
                <option value="<?= e((string) $languageKey) ?>"<?= (string) ($story['language'] ?? '') === (string) $languageKey ? ' selected' : '' ?>><?= e((string) $languageLabel) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['language'])): ?><div class="invalid-feedback"><?= e((string) $errors['language']) ?></div><?php endif; ?>
    </div>
    <div class="col-md-3">
        <label class="form-label">Visibility</label>
        <select class="form-select<?= isset($errors['visibility']) ? ' is-invalid' : '' ?>" name="visibility">
            <?php foreach ($visibilityOptions as $visibilityKey => $visibilityLabel): ?>
                <option value="<?= e((string) $visibilityKey) ?>"<?= (string) ($story['visibility'] ?? '') === (string) $visibilityKey ? ' selected' : '' ?>><?= e((string) $visibilityLabel) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['visibility'])): ?><div class="invalid-feedback"><?= e((string) $errors['visibility']) ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
        <label class="form-label">Genre</label>
        <input class="form-control" name="genre" value="<?= e((string) ($story['genre'] ?? '')) ?>" placeholder="Cerpen, Novel, Artikel, Audio Story, Podcast">
    </div>
    <div class="col-md-6">
        <label class="form-label">Tags</label>
        <input class="form-control" name="tags" value="<?= e((string) ($story['tags_string'] ?? ($story['tags'] ?? ''))) ?>" placeholder="ramadhan, horor, ai">
    </div>
    <div class="col-12">
        <label class="form-label">Summary</label>
        <textarea class="form-control<?= isset($errors['summary']) ? ' is-invalid' : '' ?>" name="summary" rows="3"><?= e((string) ($story['summary'] ?? '')) ?></textarea>
        <?php if (isset($errors['summary'])): ?><div class="invalid-feedback"><?= e((string) $errors['summary']) ?></div><?php endif; ?>
    </div>
    <div class="col-12">
        <label class="form-label">Body</label>
        <textarea class="form-control<?= isset($errors['body']) ? ' is-invalid' : '' ?>" name="body" rows="12"><?= e((string) ($story['body'] ?? '')) ?></textarea>
        <?php if (isset($errors['body'])): ?><div class="invalid-feedback"><?= e((string) $errors['body']) ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
        <label class="form-label">SEO Title</label>
        <input class="form-control" name="seo_title" value="<?= e((string) ($story['seo_title'] ?? '')) ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">SEO Description</label>
        <input class="form-control" name="seo_description" value="<?= e((string) ($story['seo_description'] ?? '')) ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Canonical URL</label>
        <input class="form-control" name="canonical_url" value="<?= e((string) ($story['canonical_url'] ?? '')) ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Open Graph Title</label>
        <input class="form-control" name="og_title" value="<?= e((string) ($story['og_title'] ?? '')) ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Open Graph Description</label>
        <input class="form-control" name="og_description" value="<?= e((string) ($story['og_description'] ?? '')) ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Open Graph Image</label>
        <input class="form-control<?= isset($errors['og_image']) ? ' is-invalid' : '' ?>" name="og_image" value="<?= e((string) ($story['og_image'] ?? '')) ?>">
        <?php if (isset($errors['og_image'])): ?><div class="invalid-feedback"><?= e((string) $errors['og_image']) ?></div><?php endif; ?>
    </div>
    <div class="col-12">
        <label class="form-label">JSON-LD Placeholder</label>
        <textarea class="form-control" name="json_ld_placeholder" rows="8"><?= e((string) ($story['json_ld_placeholder'] ?? '')) ?></textarea>
    </div>
    <div class="col-12 d-flex gap-2 flex-wrap">
        <button class="btn btn-dark" type="submit"><?= e((string) $submitLabel) ?></button>
        <a class="btn btn-outline-secondary" href="/story">Kembali</a>
    </div>
</form>
