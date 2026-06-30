<?php
$story = $story ?? [];
$errors = $errors ?? [];
$languageOptions = $languageOptions ?? [];
$visibilityOptions = $visibilityOptions ?? [];
$collections = $collections ?? [];
$flash = $flash ?? null;
require base_path('app/modules/Story/views/partials/flash.php');
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
            <div>
                <span class="badge text-bg-dark mb-2">Story</span>
                <h1 class="h4 mb-0">Edit Story</h1>
            </div>
            <a class="btn btn-outline-secondary" href="/story/<?= e((string) ($story['id'] ?? 0)) ?>">Kembali ke Detail</a>
        </div>
        <?php
        $formAction = '/story/' . (string) ($story['id'] ?? 0) . '/edit';
        $submitLabel = 'Simpan Perubahan';
        require base_path('app/modules/Story/views/partials/form.php');
        ?>
    </div>
</div>
