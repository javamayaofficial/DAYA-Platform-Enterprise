<?php
$pagination = $pagination ?? [];
$basePath = $paginationBasePath ?? '';
$query = $paginationQuery ?? [];
$currentPage = (int) ($pagination['page'] ?? 1);
$lastPage = (int) ($pagination['last_page'] ?? 1);
?>
<?php if ($lastPage > 1): ?>
    <nav aria-label="Pagination">
        <ul class="pagination mb-0">
            <?php for ($page = 1; $page <= $lastPage; $page++): ?>
                <?php $queryString = http_build_query(array_merge($query, ['page' => $page])); ?>
                <li class="page-item<?= $page === $currentPage ? ' active' : '' ?>">
                    <a class="page-link" href="<?= e($basePath . ($queryString !== '' ? '?' . $queryString : '')) ?>">
                        <?= e((string) $page) ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>
