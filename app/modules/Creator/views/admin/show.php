<?php
$auth = $auth ?? [];
$creator = $creator ?? [];
$statistics = $statistics ?? [];
$statuses = $statuses ?? [];
$creatorLevels = $creatorLevels ?? [];
$verificationStatuses = $verificationStatuses ?? [];
$badgeCatalog = $badgeCatalog ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Creator/views/partials/flash.php');
$application = is_array($creator['application'] ?? null) ? $creator['application'] : [];
$socialLinks = is_array($creator['social_links'] ?? null) ? $creator['social_links'] : [];
$portfolioItems = is_array($creator['portfolio_items'] ?? null) ? $creator['portfolio_items'] : [];
$badgeItems = is_array($creator['badges'] ?? null) ? $creator['badges'] : [];
$selectedBadgeKeys = array_map(static fn (array $badge): string => (string) ($badge['badge_key'] ?? ''), $badgeItems);
?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
                    <div>
                        <span class="badge text-bg-dark mb-2">Admin</span>
                        <h1 class="h4 mb-0"><?= e((string) ($creator['display_name'] ?? '-')) ?></h1>
                    </div>
                    <a class="btn btn-outline-secondary" href="/creator/admin">Kembali</a>
                </div>

                <div class="row g-3">
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Creator Code</div><div class="status-value"><?= e((string) ($creator['creator_code'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Slug</div><div class="status-value">@<?= e((string) ($creator['slug'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Owner</div><div class="status-value"><?= e((string) ($creator['user_email'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Status</div><div class="status-value"><?= e((string) ($creator['status'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">KYC</div><div class="status-value"><?= e((string) ($creator['kyc_status'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Verification</div><div class="status-value"><?= e((string) ($creator['verification_status'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Category</div><div class="status-value"><?= e((string) ($creator['category'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Deleted At</div><div class="status-value"><?= e((string) (($creator['deleted_at'] ?? '') !== '' ? $creator['deleted_at'] : '-')) ?></div></div></div>
                </div>

                <div class="mt-4">
                    <h2 class="h5 mb-2">Profile Summary</h2>
                    <p class="text-secondary mb-2"><?= e((string) ($creator['tagline'] ?? '-')) ?></p>
                    <p class="mb-3"><?= nl2br(e((string) ($creator['bio'] ?? '-'))) ?></p>
                    <div class="small text-secondary">Admin reviewer: <?= e((string) ($auth['email'] ?? '-')) ?></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Application & KYC</h2>
                <div class="row g-3">
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Applicant Name</div><div class="status-value"><?= e((string) ($application['kyc_full_name'] ?? '-')) ?></div></div></div>
                    <div class="col-md-6"><div class="status-card"><div class="status-label">Document Type</div><div class="status-value"><?= e((string) ($application['kyc_document_type'] ?? '-')) ?></div></div></div>
                </div>
                <div class="small text-secondary mt-3">Application Note</div>
                <p class="mb-3"><?= nl2br(e((string) ($application['application_note'] ?? '-'))) ?></p>
                <div class="small text-secondary">Review Notes</div>
                <p class="mb-0"><?= nl2br(e((string) ($creator['review_notes'] ?? '-'))) ?></p>
                <?php if (($application['kyc_document_url'] ?? '') !== ''): ?>
                    <a class="btn btn-sm btn-outline-dark mt-3" href="<?= e((string) $application['kyc_document_url']) ?>" target="_blank" rel="noopener">Buka Dokumen KYC</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Review Creator</h2>
                <form method="post" action="/creator/admin/<?= e((string) ($creator['id'] ?? 0)) ?>/review" class="row g-3">
                    <?= csrf_input() ?>
                    <div class="col-md-4">
                        <select class="form-select" name="status">
                            <?php foreach ($statuses as $statusSlug => $statusLabel): ?>
                                <?php if (in_array((string) $statusSlug, ['active', 'rejected', 'suspended', 'revoked'], true)): ?>
                                    <option value="<?= e((string) $statusSlug) ?>"<?= (string) ($creator['status'] ?? '') === (string) $statusSlug ? ' selected' : '' ?>><?= e((string) $statusLabel) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="verification_status">
                            <?php foreach ($verificationStatuses as $statusKey => $statusLabel): ?>
                                <option value="<?= e((string) $statusKey) ?>"<?= (string) ($creator['verification_status'] ?? '') === (string) $statusKey ? ' selected' : '' ?>><?= e((string) $statusLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="creator_level">
                            <?php foreach ($creatorLevels as $levelKey => $levelLabel): ?>
                                <option value="<?= e((string) $levelKey) ?>"<?= (string) ($creator['creator_level'] ?? '') === (string) $levelKey ? ' selected' : '' ?>><?= e((string) $levelLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control" type="number" min="0" name="creator_rank" value="<?= e((string) ($creator['creator_rank'] ?? 0)) ?>" placeholder="Creator Rank">
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" name="review_notes" value="<?= e((string) ($creator['review_notes'] ?? '')) ?>" placeholder="Catatan review admin">
                    </div>
                    <div class="col-12">
                        <div class="small text-secondary mb-2">Badge</div>
                        <div class="d-flex gap-3 flex-wrap">
                            <?php foreach ($badgeCatalog as $badgeKey => $badgeLabel): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="badges[]" value="<?= e((string) $badgeKey) ?>" id="badge-<?= e((string) $badgeKey) ?>"<?= in_array((string) $badgeKey, $selectedBadgeKeys, true) ? ' checked' : '' ?>>
                                    <label class="form-check-label" for="badge-<?= e((string) $badgeKey) ?>"><?= e((string) $badgeLabel) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-4"><input class="form-control" type="number" min="0" name="followers_count" value="<?= e((string) ($statistics['followers_count'] ?? 0)) ?>" placeholder="Followers"></div>
                    <div class="col-md-4"><input class="form-control" type="number" min="0" name="reads_count" value="<?= e((string) ($statistics['reads_count'] ?? 0)) ?>" placeholder="Reads"></div>
                    <div class="col-md-4"><input class="form-control" type="number" min="0" name="listens_count" value="<?= e((string) ($statistics['listens_count'] ?? 0)) ?>" placeholder="Listens"></div>
                    <div class="col-md-4"><input class="form-control" type="number" min="0" name="downloads_count" value="<?= e((string) ($statistics['downloads_count'] ?? 0)) ?>" placeholder="Downloads"></div>
                    <div class="col-md-4"><input class="form-control" type="number" min="0" name="sponsor_count" value="<?= e((string) ($statistics['sponsor_count'] ?? 0)) ?>" placeholder="Sponsor"></div>
                    <div class="col-md-4"><input class="form-control" type="number" min="0" name="donation_count" value="<?= e((string) ($statistics['donation_count'] ?? 0)) ?>" placeholder="Donation"></div>
                    <div class="col-md-4"><input class="form-control" type="number" min="0" name="affiliate_count" value="<?= e((string) ($statistics['affiliate_count'] ?? 0)) ?>" placeholder="Affiliate"></div>
                    <div class="col-md-4"><input class="form-control" type="number" min="0" name="revenue_minor" value="<?= e((string) ($statistics['revenue_minor'] ?? 0)) ?>" placeholder="Revenue Minor"></div>
                    <div class="col-md-4"><input class="form-control" type="number" min="0" name="wallet_available_minor" value="<?= e((string) ($statistics['wallet_available_minor'] ?? 0)) ?>" placeholder="Wallet Available"></div>
                    <div class="col-md-4"><input class="form-control" type="number" min="0" name="wallet_pending_minor" value="<?= e((string) ($statistics['wallet_pending_minor'] ?? 0)) ?>" placeholder="Wallet Pending"></div>
                    <div class="col-12 d-grid d-md-flex">
                        <button class="btn btn-dark" type="submit">Simpan Review</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Portfolio & Social</h2>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h3 class="h6">Social Links</h3>
                        <ul class="list-group">
                            <?php foreach ($socialLinks as $link): ?>
                                <li class="list-group-item">
                                    <div class="fw-semibold"><?= e((string) ($link['platform'] ?? '-')) ?></div>
                                    <a href="<?= e((string) ($link['url'] ?? '#')) ?>" target="_blank" rel="noopener"><?= e((string) ($link['url'] ?? '-')) ?></a>
                                </li>
                            <?php endforeach; ?>
                            <?php if ($socialLinks === []): ?><li class="list-group-item text-secondary">Belum ada social link.</li><?php endif; ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h3 class="h6">Portfolio</h3>
                        <ul class="list-group">
                            <?php foreach ($portfolioItems as $item): ?>
                                <li class="list-group-item">
                                    <div class="fw-semibold"><?= e((string) ($item['title'] ?? '-')) ?></div>
                                    <div class="small text-secondary"><?= e((string) ($item['summary'] ?? '')) ?></div>
                                </li>
                            <?php endforeach; ?>
                            <?php if ($portfolioItems === []): ?><li class="list-group-item text-secondary">Belum ada portfolio.</li><?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Statistik</h2>
                <div class="row g-3">
                    <div class="col-12"><div class="status-card"><div class="status-label">Profile Views</div><div class="status-value"><?= e((string) ($statistics['profile_views'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Followers</div><div class="status-value"><?= e((string) ($statistics['followers_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Reads / Listens</div><div class="status-value"><?= e((string) ($statistics['reads_count'] ?? 0)) ?> / <?= e((string) ($statistics['listens_count'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Revenue Snapshot</div><div class="status-value"><?= e((string) ($statistics['revenue_minor'] ?? 0)) ?></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
