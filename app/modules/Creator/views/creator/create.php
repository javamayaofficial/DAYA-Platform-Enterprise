<?php
$errors = $errors ?? [];
$old = $old ?? [];
$categories = $categories ?? [];
$creatorTypes = $creatorTypes ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Creator/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Creator</span>
                <h1 class="h4 mb-0">Registrasi Creator Identity</h1>
            </div>
            <a class="btn btn-outline-secondary" href="/creator">Kembali</a>
        </div>

        <form method="post" action="/creator/register" class="row g-3">
            <?= csrf_input() ?>

            <div class="col-md-4">
                <label class="form-label">Handle</label>
                <input class="form-control" name="handle" value="<?= e((string) ($old['handle'] ?? '')) ?>" placeholder="creator-handle">
                <?php if (isset($errors['handle'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['handle']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label class="form-label">Slug Publik</label>
                <input class="form-control" name="slug" value="<?= e((string) ($old['slug'] ?? '')) ?>" placeholder="creator-slug">
                <?php if (isset($errors['slug'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['slug']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label class="form-label">Creator Type</label>
                <select class="form-select" name="creator_type">
                    <?php foreach ($creatorTypes as $typeKey => $typeLabel): ?>
                        <option value="<?= e((string) $typeKey) ?>"<?= (string) ($old['creator_type'] ?? '') === (string) $typeKey ? ' selected' : '' ?>><?= e((string) $typeLabel) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['creator_type'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['creator_type']) ?></div><?php endif; ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Display Name</label>
                <input class="form-control" name="display_name" value="<?= e((string) ($old['display_name'] ?? '')) ?>">
                <?php if (isset($errors['display_name'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['display_name']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Primary Category</label>
                <select class="form-select" name="category">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= e((string) $category) ?>"<?= (string) ($old['category'] ?? '') === (string) $category ? ' selected' : '' ?>><?= e((string) $category) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['category'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['category']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Multi Category</label>
                <textarea class="form-control" name="categories" rows="3" placeholder="Pisahkan dengan koma atau baris baru"><?= e((string) (is_array($old['categories'] ?? null) ? implode(', ', $old['categories']) : ($old['categories'] ?? ''))) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Multi Skill</label>
                <textarea class="form-control" name="skills" rows="3" placeholder="Contoh: Writing, Public Speaking, Audio Editing"><?= e((string) (is_array($old['skills'] ?? null) ? implode(', ', $old['skills']) : ($old['skills'] ?? ''))) ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Location</label>
                <input class="form-control" name="location" value="<?= e((string) ($old['location'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Website URL</label>
                <input class="form-control" name="website_url" value="<?= e((string) ($old['website_url'] ?? '')) ?>">
                <?php if (isset($errors['website_url'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['website_url']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Avatar URL</label>
                <input class="form-control" name="avatar_url" value="<?= e((string) ($old['avatar_url'] ?? '')) ?>">
                <?php if (isset($errors['avatar_url'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['avatar_url']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Cover Image URL</label>
                <input class="form-control" name="cover_image_url" value="<?= e((string) ($old['cover_image_url'] ?? '')) ?>">
                <?php if (isset($errors['cover_image_url'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['cover_image_url']) ?></div><?php endif; ?>
            </div>
            <div class="col-12">
                <label class="form-label">Tagline</label>
                <input class="form-control" name="tagline" value="<?= e((string) ($old['tagline'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Bio</label>
                <textarea class="form-control" name="bio" rows="4"><?= e((string) ($old['bio'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Public Email</label>
                <input class="form-control" name="public_email" value="<?= e((string) ($old['public_email'] ?? '')) ?>">
                <?php if (isset($errors['public_email'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['public_email']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">SEO Title</label>
                <input class="form-control" name="seo_title" value="<?= e((string) ($old['seo_title'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">SEO Description</label>
                <textarea class="form-control" name="seo_description" rows="2"><?= e((string) ($old['seo_description'] ?? '')) ?></textarea>
            </div>

            <div class="col-12"><hr class="my-1"></div>

            <div class="col-md-6">
                <label class="form-label">KYC Full Name</label>
                <input class="form-control" name="kyc_full_name" value="<?= e((string) ($old['kyc_full_name'] ?? '')) ?>">
                <?php if (isset($errors['kyc_full_name'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['kyc_full_name']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Document Type</label>
                <input class="form-control" name="kyc_document_type" value="<?= e((string) ($old['kyc_document_type'] ?? '')) ?>" placeholder="KTP / Passport / SIM">
                <?php if (isset($errors['kyc_document_type'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['kyc_document_type']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Document Number</label>
                <input class="form-control" name="kyc_document_number" value="<?= e((string) ($old['kyc_document_number'] ?? '')) ?>">
                <?php if (isset($errors['kyc_document_number'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['kyc_document_number']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Document URL</label>
                <input class="form-control" name="kyc_document_url" value="<?= e((string) ($old['kyc_document_url'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Application Note</label>
                <textarea class="form-control" name="application_note" rows="3"><?= e((string) ($old['application_note'] ?? '')) ?></textarea>
                <?php if (isset($errors['application_note'])): ?><div class="text-danger small mt-1"><?= e((string) $errors['application_note']) ?></div><?php endif; ?>
            </div>

            <div class="col-12"><hr class="my-1"></div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="public_page_enabled" value="1" id="publicPageEnabled"<?= !empty($old['public_page_enabled']) ? ' checked' : '' ?>>
                    <label class="form-check-label" for="publicPageEnabled">Aktifkan halaman publik setelah disetujui</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allow_public_contact" value="1" id="allowPublicContact"<?= !empty($old['allow_public_contact']) ? ' checked' : '' ?>>
                    <label class="form-check-label" for="allowPublicContact">Tampilkan email kontak publik</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="show_portfolio_publicly" value="1" id="showPortfolioPublicly"<?= !empty($old['show_portfolio_publicly']) ? ' checked' : '' ?>>
                    <label class="form-check-label" for="showPortfolioPublicly">Tampilkan portfolio di halaman publik</label>
                </div>
            </div>

            <div class="col-12 d-flex gap-2 flex-wrap">
                <button class="btn btn-dark" type="submit">Submit Creator</button>
                <a class="btn btn-outline-secondary" href="/creator">Batal</a>
            </div>
        </form>
    </div>
</div>
