<?php
$errors = $errors ?? [];
$old = $old ?? [];
$visibilityOptions = $visibilityOptions ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Collection/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <span class="badge text-bg-dark mb-3">Collection</span>
        <h1 class="h4 mb-3">Create Collection</h1>
        <p class="text-secondary">Buat wadah untuk mengelompokkan banyak Content milik Creator yang sama.</p>
        <?php
        $formAction = '/collection/create';
        $submitLabel = 'Simpan Collection';
        $collection = $old;
        require base_path('app/modules/Collection/views/partials/form.php');
        ?>
    </div>
</div>
