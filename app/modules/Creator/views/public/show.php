<?php
$creator = $creator ?? [];
$statistics = $statistics ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Creator/views/partials/flash.php');
$socialLinks = is_array($creator['social_links'] ?? null) ? $creator['social_links'] : [];
$portfolioItems = is_array($creator['portfolio_items'] ?? null) ? $creator['portfolio_items'] : [];
$badgeItems = is_array($creator['badges'] ?? null) ? $creator['badges'] : [];
$achievementItems = is_array($creator['achievements'] ?? null) ? $creator['achievements'] : [];
$skillItems = is_array($creator['skills'] ?? null) ? $creator['skills'] : [];
?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <span class="badge text-bg-dark mb-3">Creator</span>
                <h1 class="h3 mb-2"><?= e((string) ($creator['display_name'] ?? '-')) ?></h1>
                <div class="text-secondary mb-3"><?= e((string) ($creator['creator_code'] ?? '-')) ?> | @<?= e((string) ($creator['slug'] ?? '-')) ?> | <?= e((string) ($creator['category'] ?? '-')) ?></div>
                <p class="mb-2"><?= e((string) ($creator['tagline'] ?? '')) ?></p>
                <p class="mb-3"><?= nl2br(e((string) ($creator['bio'] ?? ''))) ?></p>
                <div class="d-flex gap-2 flex-wrap mb-3">
                    <?php foreach ($badgeItems as $badge): ?>
                        <span class="badge text-bg-warning"><?= e((string) ($badge['badge_label'] ?? '-')) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="d-flex gap-2 flex-wrap mb-3">
                    <?php foreach ($skillItems as $skill): ?>
                        <span class="badge text-bg-dark"><?= e((string) ($skill['name'] ?? '-')) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="small text-secondary">
                    Lokasi: <?= e((string) (($creator['location'] ?? '') !== '' ? $creator['location'] : '-')) ?>
                    <?php if (($creator['website_url'] ?? '') !== ''): ?>
                        | Website: <a href="<?= e((string) $creator['website_url']) ?>" target="_blank" rel="noopener"><?= e((string) $creator['website_url']) ?></a>
                    <?php endif; ?>
                    <?php if (!empty($creator['allow_public_contact']) && ($creator['public_email'] ?? '') !== ''): ?>
                        | Kontak: <?= e((string) $creator['public_email']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($achievementItems !== []): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Achievement</h2>
                    <div class="row g-3">
                        <?php foreach ($achievementItems as $achievement): ?>
                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <div class="fw-semibold"><?= e((string) ($achievement['title'] ?? '-')) ?></div>
                                    <div class="small text-secondary"><?= e((string) ($achievement['issuer'] ?? '-')) ?> | <?= e((string) ($achievement['achieved_at'] ?? '-')) ?></div>
                                    <div class="small mt-2"><?= e((string) ($achievement['description'] ?? '')) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Social Links</h2>
                <div class="d-flex gap-2 flex-wrap">
                    <?php foreach ($socialLinks as $link): ?>
                        <a class="btn btn-outline-dark btn-sm" href="<?= e((string) ($link['url'] ?? '#')) ?>" target="_blank" rel="noopener">
                            <?= e(ucfirst((string) ($link['platform'] ?? 'link'))) ?>
                        </a>
                    <?php endforeach; ?>
                    <?php if ($socialLinks === []): ?><div class="text-secondary">Creator ini belum menambahkan social link.</div><?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($creator['show_portfolio_publicly'])): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Portfolio</h2>
                    <div class="row g-3">
                        <?php foreach ($portfolioItems as $item): ?>
                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <div class="fw-semibold"><?= e((string) ($item['title'] ?? '-')) ?><?php if (!empty($item['is_featured'])): ?> <span class="badge text-bg-warning">Featured</span><?php endif; ?></div>
                                    <div class="small text-secondary mb-2"><?= e((string) ($item['summary'] ?? '')) ?></div>
                                    <a href="<?= e((string) ($item['url'] ?? '#')) ?>" target="_blank" rel="noopener"><?= e((string) ($item['url'] ?? '-')) ?></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if ($portfolioItems === []): ?><div class="col-12 text-secondary">Belum ada portfolio publik.</div><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Statistik</h2>
                <div class="row g-3">
                    <div class="col-12"><div class="status-card"><div class="status-label">Profile Views</div><div class="status-value"><?= e((string) ($statistics['profile_views'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Followers</div><div class="status-value"><?= e((string) ($statistics['followers_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Reads / Listens</div><div class="status-value"><?= e((string) ($statistics['reads_count'] ?? 0)) ?> / <?= e((string) ($statistics['listens_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Downloads</div><div class="status-value"><?= e((string) ($statistics['downloads_count'] ?? 0)) ?></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
