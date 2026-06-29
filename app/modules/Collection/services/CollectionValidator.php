<?php

declare(strict_types=1);

namespace App\Modules\Collection\Services;

final class CollectionValidator
{
    public function __construct(
        private readonly array $statuses,
        private readonly array $visibilityOptions
    ) {
    }

    public function validateCollection(array $collection): array
    {
        $errors = [];

        if (trim((string) ($collection['title'] ?? '')) === '') {
            $errors['title'] = 'Title is required.';
        }

        if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,99}$/', (string) ($collection['slug'] ?? ''))) {
            $errors['slug'] = 'Slug must be 3-100 chars using lowercase letters, numbers, or hyphen.';
        }

        if (!in_array((string) ($collection['visibility'] ?? ''), $this->visibilityOptions, true)) {
            $errors['visibility'] = 'Visibility is not valid.';
        }

        $coverImageUrl = trim((string) ($collection['cover_image_url'] ?? ''));
        if ($coverImageUrl !== '' && filter_var($coverImageUrl, FILTER_VALIDATE_URL) === false) {
            $errors['cover_image_url'] = 'Cover image URL is not valid.';
        }

        return ['errors' => $errors];
    }

    public function validateStatus(string $status): array
    {
        $errors = [];

        if (!in_array($status, $this->statuses, true)) {
            $errors['status'] = 'Collection status is not valid.';
        }

        return ['errors' => $errors];
    }

    public function validateItemContentId(int $contentId): array
    {
        $errors = [];

        if ($contentId <= 0) {
            $errors['content_id'] = 'Content is required.';
        }

        return ['errors' => $errors];
    }

    public function validateItemOrders(array $orders): array
    {
        $errors = [];

        foreach ($orders as $itemId => $sortOrder) {
            if ((int) $itemId <= 0 || (int) $sortOrder <= 0) {
                $errors['item_orders'] = 'Collection item order is not valid.';
                break;
            }
        }

        return ['errors' => $errors];
    }
}
