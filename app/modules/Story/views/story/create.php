<?php
$errors = $errors ?? [];
$old = $old ?? [];
$languageOptions = $languageOptions ?? [];
$visibilityOptions = $visibilityOptions ?? [];
$collections = $collections ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Story/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <span class="badge text-bg-dark mb-3">Story</span>
        <h1 class="h4 mb-3">Create Story</h1>
        <p class="text-secondary">Buat karya utama untuk Reader dengan metadata SEO, visibilitas, dan relasi Collection opsional.</p>
        <?php
        $formAction = '/story/create';
        $submitLabel = 'Simpan Story';
        $story = $old;
        require base_path('app/modules/Story/views/partials/form.php');
        ?>
    </div>
</div>
