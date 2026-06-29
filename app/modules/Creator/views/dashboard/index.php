<?php
$auth = $auth ?? [];
$creator = $creator ?? null;
$statistics = $statistics ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Creator/views/partials/flash.php');
?>
<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <span class="badge text-bg-dark mb-3">Creator</span>
                <h1 class="h4 mb-3">Dashboard Creator</h1>
                <p class="text-secondary">
                    Area ini merangkum status pendaftaran Creator, performa profil, dan akses cepat ke pengelolaan profil publik.
                </p>

                <?php if (is_array($creator)): ?>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Display Name</div><div class="status-value"><?= e((string) ($creator['display_name'] ?? '-')) ?></div></div></div>
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Creator Code</div><div class="status-value"><?= e((string) ($creator['creator_code'] ?? '-')) ?></div></div></div>
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Slug</div><div class="status-value">@<?= e((string) ($creator['slug'] ?? '-')) ?></div></div></div>
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Status</div><div class="status-value"><?= e((string) ($creator['status'] ?? '-')) ?></div></div></div>
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Verification</div><div class="status-value"><?= e((string) ($creator['verification_status'] ?? '-')) ?></div></div></div>
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Category</div><div class="status-value"><?= e((string) ($creator['category'] ?? '-')) ?></div></div></div>
                        <div class="col-md-6"><div class="status-card"><div class="status-label">Creator Level</div><div class="status-value"><?= e((string) ($creator['creator_level'] ?? '-')) ?></div></div></div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap mt-4">
                        <a class="btn btn-dark" href="/creator/profile">Lihat Profil</a>
                        <a class="btn btn-outline-dark" href="/creator/profile/edit">Edit Profil</a>
                        <?php if (($creator['status'] ?? '') === 'active' && !empty($creator['public_page_enabled'])): ?>
                            <a class="btn btn-outline-secondary" href="/creators/<?= e((string) ($creator['handle'] ?? '')) ?>" target="_blank" rel="noopener">Halaman Publik</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        Akun ini belum memiliki profil Creator. Lengkapi pendaftaran untuk membuka dashboard Creator.
                    </div>
                    <div class="mt-4">
                        <a class="btn btn-dark" href="/creator/register">Buat Profil Creator</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Ringkasan Akun</h2>
                <div class="small text-secondary mb-3"><?= e((string) ($auth['email'] ?? '-')) ?></div>
                <div class="row g-3">
                    <div class="col-12"><div class="status-card"><div class="status-label">Profile Views</div><div class="status-value"><?= e((string) ($statistics['profile_views'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Social Links</div><div class="status-value"><?= e((string) ($statistics['social_links'] ?? 0)) ?></div></div></div>
                    <div class="col-12"><div class="status-card"><div class="status-label">Portfolio Items</div><div class="status-value"><?= e((string) ($statistics['portfolio_items'] ?? 0)) ?></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
