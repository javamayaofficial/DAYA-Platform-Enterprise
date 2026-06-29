<?php
$creator = $creator ?? [];
$categories = $categories ?? [];
$creatorTypes = $creatorTypes ?? [];
$errors = $errors ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Creator/views/partials/flash.php');
$creatorCategoryNames = array_map(static fn (array $item): string => (string) ($item['name'] ?? ''), (array) ($creator['categories'] ?? []));
$creatorSkillNames = array_map(static fn (array $item): string => (string) ($item['name'] ?? ''), (array) ($creator['skills'] ?? []));
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Creator</span>
                <h1 class="h4 mb-0">Edit Creator Identity</h1>
            </div>
            <a class="btn btn-outline-secondary" href="/creator/profile">Kembali</a>
        </div>

        <form method="post" action="/creator/profile/edit" class="row g-3">
            <input type="hidden" name="_csrf_token" value="<?= e($_SESSION['_csrf_token'] ?? '') ?>">

            <div class="col-md-4">
                <label class="form-label">Handle</label>
                <input class="form-control" name="handle" value="<?= e((string) ($creator['handle'] ?? '')) ?>">
                <?php if (isset($errors['handle'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['handle']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label class="form-label">Slug Publik</label>
                <input class="form-control" name="slug" value="<?= e((string) ($creator['slug'] ?? '')) ?>">
                <?php if (isset($errors['slug'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['slug']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label class="form-label">Creator Type</label>
                <select class="form-select" name="creator_type">
                    <?php foreach ($creatorTypes as $typeKey => $typeLabel): ?>
                        <option value="<?= e((string) $typeKey) ?>"<?= (string) ($creator['creator_type'] ?? '') === (string) $typeKey ? ' selected' : '' ?>><?= e((string) $typeLabel) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['creator_type'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['creator_type']) ?></div><?php endif; ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Display Name</label>
                <input class="form-control" name="display_name" value="<?= e((string) ($creator['display_name'] ?? '')) ?>">
                <?php if (isset($errors['display_name'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['display_name']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Primary Category</label>
                <select class="form-select" name="category">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= e((string) $category) ?>"<?= (string) ($creator['category'] ?? '') === (string) $category ? ' selected' : '' ?>><?= e((string) $category) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['category'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['category']) ?></div><?php endif; ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Multi Category</label>
                <textarea class="form-control" name="categories" rows="3"><?= e(implode(', ', $creatorCategoryNames)) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Multi Skill</label>
                <textarea class="form-control" name="skills" rows="3"><?= e(implode(', ', $creatorSkillNames)) ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Location</label>
                <input class="form-control" name="location" value="<?= e((string) ($creator['location'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Website URL</label>
                <input class="form-control" name="website_url" value="<?= e((string) ($creator['website_url'] ?? '')) ?>">
                <?php if (isset($errors['website_url'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['website_url']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Avatar URL</label>
                <input class="form-control" name="avatar_url" value="<?= e((string) ($creator['avatar_url'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Cover Image URL</label>
                <input class="form-control" name="cover_image_url" value="<?= e((string) ($creator['cover_image_url'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Tagline</label>
                <input class="form-control" name="tagline" value="<?= e((string) ($creator['tagline'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bio</label>
                <textarea class="form-control" name="bio" rows="4"><?= e((string) ($creator['bio'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Public Email</label>
                <input class="form-control" name="public_email" value="<?= e((string) ($creator['public_email'] ?? '')) ?>">
                <?php if (isset($errors['public_email'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['public_email']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">SEO Title</label>
                <input class="form-control" name="seo_title" value="<?= e((string) ($creator['seo_title'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">SEO Description</label>
                <textarea class="form-control" name="seo_description" rows="2"><?= e((string) ($creator['seo_description'] ?? '')) ?></textarea>
            </div>

            <div class="col-12 d-flex gap-2 flex-wrap">
                <button class="btn btn-dark" type="submit">Simpan Perubahan</button>
                <a class="btn btn-outline-secondary" href="/creator/profile">Batal</a>
            </div>
        </form>
    </div>
</div>
