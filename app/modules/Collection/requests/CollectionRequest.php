<?php

declare(strict_types=1);

namespace App\Modules\Collection\Requests;

use App\Core\Modular\BaseRequest;

final class CollectionRequest extends BaseRequest
{
    public function userId(): int
    {
        $auth = $this->session()->get('auth', []);

        return is_array($auth) ? (int) ($auth['user_id'] ?? 0) : 0;
    }

    public function slug(): string
    {
        $source = trim((string) $this->input('slug', ''));
        if ($source === '') {
            $source = trim((string) $this->input('title', ''));
        }

        $slug = strtolower((string) preg_replace('/[^a-z0-9]+/', '-', $source));

        return trim($slug, '-');
    }

    public function collectionData(): array
    {
        return [
            'title' => $this->string('title'),
            'slug' => $this->slug(),
            'description' => $this->string('description'),
            'cover_image_url' => $this->string('cover_image_url'),
            'visibility' => $this->string('visibility', 'public'),
        ];
    }

    public function itemContentId(): int
    {
        return $this->integer('content_id');
    }

    public function itemOrders(): array
    {
        $raw = $this->input('item_orders', []);
        if (!is_array($raw)) {
            return [];
        }

        $orders = [];
        foreach ($raw as $itemId => $sortOrder) {
            $orders[(int) $itemId] = max(1, (int) $sortOrder);
        }

        return $orders;
    }

    public function page(): int
    {
        return max(1, (int) $this->query('page', 1));
    }

    public function perPage(): int
    {
        return max(1, min(50, (int) $this->query('per_page', 10)));
    }

    public function statusFilter(): string
    {
        return trim((string) $this->query('status', ''));
    }

    public function visibilityFilter(): string
    {
        return trim((string) $this->query('visibility', ''));
    }

    public function includeDeleted(): bool
    {
        return $this->boolean('include_deleted');
    }

    public function collectionId(): int
    {
        return (int) $this->route('id', 0);
    }

    public function itemId(): int
    {
        return (int) $this->route('itemId', 0);
    }
}
