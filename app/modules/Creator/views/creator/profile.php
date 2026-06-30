<?php
$creator = $creator ?? [];
$statistics = $statistics ?? [];
$socialPlatforms = $socialPlatforms ?? [];
$portfolioTypes = $portfolioTypes ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Creator/views/partials/flash.php');
$application = is_array($creator['application'] ?? null) ? $creator['application'] : [];
$socialLinks = is_array($creator['social_links'] ?? null) ? $creator['social_links'] : [];
$portfolioItems = is_array($creator['portfolio_items'] ?? null) ? $creator['portfolio_items'] : [];
$achievementItems = is_array($creator['achievements'] ?? null) ? $creator['achievements'] : [];
$badgeItems = is_array($creator['badges'] ?? null) ? $creator['badges'] : [];
$categoryItems = is_array($creator['categories'] ?? null) ? $creator['categories'] : [];
$skillItems = is_array($creator['skills'] ?? null) ? $creator['skills'] : [];
?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
                    <div>
                        <span class="badge text-bg-dark mb-2">Creator</span>
                        <h1 class="h4 mb-0"><?= e((string) ($creator['display_name'] ?? '-')) ?></h1>
                        <div class="small text-secondary mt-1">
                            <?= e((string) ($creator['creator_code'] ?? '-')) ?> |
                            @<?= e((string) ($creator['handle'] ?? '-')) ?> |
                            <?= e((string) ($creator['creator_type'] ?? '-')) ?>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="btn btn-dark" href="/creator/profile/edit">Edit Profil</a>
                        <?php if (($creator['status'] ?? '') === 'active' && !empty($creator['public_page_enabled'])): ?>
                            <a class="btn btn-outline-dark" href="<?= e((string) ($creator['public_url'] ?? '#')) ?>" target="_blank" rel="noopener">Halaman Publik</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Slug</div><div class="status-value"><?= e((string) ($creator['slug'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Verification</div><div class="status-value"><?= e((string) ($creator['verification_status'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Level</div><div class="status-value"><?= e((string) ($creator['creator_level'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Rank</div><div class="status-value">#<?= e((string) ($creator['creator_rank'] ?? 0)) ?></div></div></div>
                </div>

                <div class="mt-4">
                    <h2 class="h5 mb-2">Profil</h2>
                    <p class="mb-2 text-secondary"><?= e((string) ($creator['tagline'] ?? '-')) ?></p>
                    <p class="mb-3"><?= nl2br(e((string) ($creator['bio'] ?? '-'))) ?></p>
                    <div class="small text-secondary mb-3">
                        Lokasi: <?= e((string) ($creator['location'] ?? '-')) ?> |
                        Website: <?= e((string) (($creator['website_url'] ?? '') !== '' ? $creator['website_url'] : '-')) ?> |
                        Email Publik: <?= e((string) (($creator['public_email'] ?? '') !== '' ? $creator['public_email'] : '-')) ?>
                    </div>
                    <div class="small text-secondary">Public URL</div>
                    <div class="fw-semibold"><?= e((string) ($creator['public_url'] ?? '-')) ?></div>
                </div>

                <div class="mt-4">
                    <h2 class="h5 mb-2">Badge</h2>
                    <div class="d-flex gap-2 flex-wrap">
                        <?php foreach ($badgeItems as $badge): ?>
                            <span class="badge text-bg-warning"><?= e((string) ($badge['badge_label'] ?? '-')) ?></span>
                        <?php endforeach; ?>
                        <?php if ($badgeItems === []): ?><span class="text-secondary small">Belum ada badge.</span><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Kategori, Skill, dan Social</h2>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="small text-secondary mb-2">Multi Category</div>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($categoryItems as $category): ?>
                                <span class="badge text-bg-light border"><?= e((string) ($category['name'] ?? '-')) ?></span>
                            <?php endforeach; ?>
                            <?php if ($categoryItems === []): ?><span class="text-secondary small">Belum ada kategori tambahan.</span><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-secondary mb-2">Multi Skill</div>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($skillItems as $skill): ?>
                                <span class="badge text-bg-dark"><?= e((string) ($skill['name'] ?? '-')) ?></span>
                            <?php endforeach; ?>
                            <?php if ($skillItems === []): ?><span class="text-secondary small">Belum ada skill.</span><?php endif; ?>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h3 class="h6 mb-3">Social Links</h3>
                <form method="post" action="/creator/profile/social-links" class="row g-3 mb-4">
                    <?= csrf_input() ?>
                    <div class="col-md-4">
                        <select class="form-select" name="platform">
                            <?php foreach ($socialPlatforms as $platformSlug => $platformLabel): ?>
                                <option value="<?= e((string) $platformSlug) ?>"><?= e((string) $platformLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input class="form-control" name="url" placeholder="https://">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-dark" type="submit">Tambah</button>
                    </div>
                </form>

                <?php if ($socialLinks === []): ?>
                    <div class="text-secondary">Belum ada social link.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Platform</th>
                                    <th>URL</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($socialLinks as $link): ?>
                                    <tr>
                                        <td><?= e((string) ($link['platform'] ?? '-')) ?></td>
                                        <td><a href="<?= e((string) ($link['url'] ?? '#')) ?>" target="_blank" rel="noopener"><?= e((string) ($link['url'] ?? '-')) ?></a></td>
                                        <td class="text-end">
                                            <form method="post" action="/creator/profile/social-links/<?= e((string) ($link['id'] ?? 0)) ?>/delete" class="d-inline">
                                                <?= csrf_input() ?>
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Portfolio</h2>
                <form method="post" action="/creator/profile/portfolio" class="row g-3 mb-4">
                    <?= csrf_input() ?>
                    <div class="col-md-4">
                        <select class="form-select" name="portfolio_type">
                            <?php foreach ($portfolioTypes as $typeKey => $typeLabel): ?>
                                <option value="<?= e((string) $typeKey) ?>"><?= e((string) $typeLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" name="title" placeholder="Judul karya / pengalaman / sertifikat">
                    </div>
                    <div class="col-md-6">
                        <input class="form-control" name="organization" placeholder="Penerbit / organisasi / institusi">
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" type="date" name="issued_at">
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" type="date" name="ended_at">
                    </div>
                    <div class="col-md-6">
                        <input class="form-control" name="url" placeholder="https://">
                    </div>
                    <div class="col-md-6">
                        <input class="form-control" name="thumbnail_url" placeholder="Thumbnail URL">
                    </div>
                    <div class="col-12">
                        <textarea class="form-control" name="summary" rows="2" placeholder="Ringkasan portfolio"></textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured">
                            <label class="form-check-label" for="isFeatured">Featured</label>
                        </div>
                    </div>
                    <div class="col-12 d-grid d-md-flex">
                        <button class="btn btn-dark" type="submit">Tambah Portfolio</button>
                    </div>
                </form>

                <?php if ($portfolioItems === []): ?>
                    <div class="text-secondary">Belum ada item portfolio.</div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($portfolioItems as $item): ?>
                            <div class="col-12">
                                <div class="border rounded p-3 d-flex justify-content-between gap-3 flex-wrap">
                                    <div>
                                        <div class="fw-semibold"><?= e((string) ($item['title'] ?? '-')) ?><?php if (!empty($item['is_featured'])): ?> <span class="badge text-bg-warning">Featured</span><?php endif; ?></div>
                                        <div class="small text-secondary mb-1"><?= e((string) strtoupper((string) ($item['portfolio_type'] ?? 'story'))) ?> | <?= e((string) (($item['organization'] ?? '') !== '' ? $item['organization'] : '-')) ?></div>
                                        <div class="small text-secondary mb-2"><?= e((string) ($item['summary'] ?? '')) ?></div>
                                        <a href="<?= e((string) ($item['url'] ?? '#')) ?>" target="_blank" rel="noopener"><?= e((string) ($item['url'] ?? '-')) ?></a>
                                    </div>
                                    <form method="post" action="/creator/profile/portfolio/<?= e((string) ($item['id'] ?? 0)) ?>/delete">
                                        <?= csrf_input() ?>
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Achievement</h2>
                <form method="post" action="/creator/profile/achievements" class="row g-3 mb-4">
                    <?= csrf_input() ?>
                    <div class="col-md-6">
                        <input class="form-control" name="achievement_title" placeholder="Judul achievement">
                    </div>
                    <div class="col-md-6">
                        <input class="form-control" name="achievement_issuer" placeholder="Issuer">
                    </div>
                    <div class="col-md-4">
                        <input class="form-control" type="date" name="achievement_date">
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" name="achievement_url" placeholder="https://">
                    </div>
                    <div class="col-12">
                        <textarea class="form-control" name="achievement_description" rows="2" placeholder="Deskripsi achievement"></textarea>
                    </div>
                    <div class="col-12 d-grid d-md-flex">
                        <button class="btn btn-dark" type="submit">Tambah Achievement</button>
                    </div>
                </form>

                <?php if ($achievementItems === []): ?>
                    <div class="text-secondary">Belum ada achievement.</div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($achievementItems as $achievement): ?>
                            <div class="col-12">
                                <div class="border rounded p-3 d-flex justify-content-between gap-3 flex-wrap">
                                    <div>
                                        <div class="fw-semibold"><?= e((string) ($achievement['title'] ?? '-')) ?></div>
                                        <div class="small text-secondary"><?= e((string) ($achievement['issuer'] ?? '-')) ?> | <?= e((string) ($achievement['achieved_at'] ?? '-')) ?></div>
                                        <div class="small mt-2"><?= e((string) ($achievement['description'] ?? '')) ?></div>
                                    </div>
                                    <form method="post" action="/creator/profile/achievements/<?= e((string) ($achievement['id'] ?? 0)) ?>/delete">
                                        <?= csrf_input() ?>
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Statistik</h2>
                <div class="row g-3">
                    <div class="col-12"><div class="status-card"><div class="status-label">Followers</div><div class="status-value"><?= e((string) ($statistics['followers_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Reads / Listens</div><div class="status-value"><?= e((string) ($statistics['reads_count'] ?? 0)) ?> / <?= e((string) ($statistics['listens_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Downloads</div><div class="status-value"><?= e((string) ($statistics['downloads_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Revenue Snapshot</div><div class="status-value"><?= e((string) ($statistics['revenue_minor'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Wallet Summary</div><div class="status-value"><?= e((string) ($statistics['wallet_available_minor'] ?? 0)) ?> / <?= e((string) ($statistics['wallet_pending_minor'] ?? 0)) ?></div></div></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Pengaturan Publik</h2>
                <form method="post" action="/creator/profile/settings">
                    <?= csrf_input() ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="public_page_enabled" value="1" id="settingsPublicPage"<?= !empty($creator['public_page_enabled']) ? ' checked' : '' ?>>
                        <label class="form-check-label" for="settingsPublicPage">Aktifkan halaman publik</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="allow_public_contact" value="1" id="settingsPublicContact"<?= !empty($creator['allow_public_contact']) ? ' checked' : '' ?>>
                        <label class="form-check-label" for="settingsPublicContact">Tampilkan email kontak</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="show_portfolio_publicly" value="1" id="settingsPortfolio"<?= !empty($creator['show_portfolio_publicly']) ? ' checked' : '' ?>>
                        <label class="form-check-label" for="settingsPortfolio">Tampilkan portfolio publik</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SEO Title</label>
                        <input class="form-control" name="seo_title" value="<?= e((string) ($creator['seo_title'] ?? '')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SEO Description</label>
                        <textarea class="form-control" name="seo_description" rows="2"><?= e((string) ($creator['seo_description'] ?? '')) ?></textarea>
                    </div>
                    <button class="btn btn-dark w-100" type="submit">Simpan Pengaturan</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Status Aplikasi</h2>
                <div class="small text-secondary mb-2">Review Notes</div>
                <p class="mb-3"><?= nl2br(e((string) ($creator['review_notes'] ?? '-'))) ?></p>
                <div class="small text-secondary mb-2">Application Note</div>
                <p class="mb-3"><?= nl2br(e((string) ($application['application_note'] ?? '-'))) ?></p>
                <div class="small text-secondary mb-2">KYC Document</div>
                <div class="small"><?= e((string) ($application['kyc_document_type'] ?? '-')) ?> / <?= e((string) ($application['kyc_document_number'] ?? '-')) ?></div>
                <?php if (($application['kyc_document_url'] ?? '') !== ''): ?>
                    <a class="btn btn-sm btn-outline-dark mt-3" href="<?= e((string) $application['kyc_document_url']) ?>" target="_blank" rel="noopener">Buka Dokumen</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3 text-danger">Danger Zone</h2>
                <form method="post" action="/creator/profile/delete" onsubmit="return confirm('Hapus profil Creator ini?');">
                    <?= csrf_input() ?>
                    <button class="btn btn-outline-danger w-100" type="submit">Soft Delete Creator</button>
                </form>
            </div>
        </div>
    </div>
</div>
