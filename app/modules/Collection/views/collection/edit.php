<?php
$collection = $collection ?? [];
$errors = $errors ?? [];
$visibilityOptions = $visibilityOptions ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Collection/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Collection</span>
                <h1 class="h4 mb-0">Edit Collection</h1>
            </div>
            <a class="btn btn-outline-secondary" href="/collection/<?= e((string) ($collection['id'] ?? 0)) ?>">Kembali ke Detail</a>
        </div>
        <?php
        $formAction = '/collection/' . (string) ($collection['id'] ?? 0) . '/edit';
        $submitLabel = 'Simpan Perubahan';
        require base_path('app/modules/Collection/views/partials/form.php');
        ?>
    </div>
</div>
