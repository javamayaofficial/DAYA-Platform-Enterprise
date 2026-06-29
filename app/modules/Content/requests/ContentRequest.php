<?php

declare(strict_types=1);

namespace App\Modules\Content\Requests;

use App\Core\Modular\BaseRequest;

final class ContentRequest extends BaseRequest
{
    public function userId(): int
    {
        $auth = $this->session()->get('auth', []);

        return is_array($auth) ? (int) ($auth['user_id'] ?? 0) : 0;
    }

    public function slug(): string
    {
        return strtolower(trim((string) $this->input('slug', '')));
    }

    public function contentData(): array
    {
        return [
            'content_type' => $this->string('content_type'),
            'title' => $this->string('title'),
            'slug' => $this->slug(),
            'excerpt' => $this->string('excerpt'),
            'body' => $this->string('body'),
            'cover_image_url' => $this->string('cover_image_url'),
            'seo_title' => $this->string('seo_title'),
            'seo_description' => $this->string('seo_description'),
            'access_policy' => $this->string('access_policy'),
            'price_minor' => $this->integer('price_minor'),
            'currency_code' => strtoupper($this->string('currency_code', 'IDR')),
            'visibility' => $this->string('visibility', 'public'),
        ];
    }

    public function partData(): array
    {
        return [
            'title' => $this->string('part_title'),
            'summary' => $this->string('part_summary'),
            'body' => $this->string('part_body'),
            'media_url' => $this->string('part_media_url'),
            'is_free_preview' => $this->boolean('part_is_free_preview'),
            'release_at' => $this->string('part_release_at'),
        ];
    }

    public function reviewData(): array
    {
        return [
            'status' => $this->string('status'),
            'review_notes' => $this->string('review_notes'),
            'reviewed_by_user_id' => $this->userId(),
        ];
    }

    public function search(): string
    {
        return trim((string) $this->query('search', ''));
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

    public function contentTypeFilter(): string
    {
        return trim((string) $this->query('content_type', ''));
    }

    public function includeDeleted(): bool
    {
        return $this->boolean('include_deleted');
    }

    public function contentId(): int
    {
        return (int) $this->route('id', 0);
    }

    public function itemId(): int
    {
        return (int) $this->route('partId', 0);
    }
}
