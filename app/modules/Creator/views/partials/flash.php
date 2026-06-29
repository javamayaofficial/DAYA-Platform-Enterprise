<?php if (is_array($flash ?? null)): ?>
    <div class="alert alert-<?= e((string) ($flash['type'] ?? 'info')) ?> mb-3">
        <?= e((string) ($flash['message'] ?? '')) ?>
    </div>
<?php endif; ?>
