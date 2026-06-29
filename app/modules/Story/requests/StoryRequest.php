<?php

declare(strict_types=1);

namespace App\Modules\Story\Requests;

use App\Core\Modular\BaseRequest;

final class StoryRequest extends BaseRequest
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

    public function storyData(): array
    {
        return [
            'collection_id' => $this->collectionIdInput(),
            'title' => $this->string('title'),
            'subtitle' => $this->string('subtitle'),
            'slug' => $this->slug(),
            'summary' => $this->string('summary'),
            'body' => $this->string('body'),
            'cover' => $this->string('cover'),
            'language' => strtolower($this->string('language', 'id')),
            'genre' => $this->string('genre'),
            'tags' => $this->normalizedTags(),
            'visibility' => $this->string('visibility', 'private'),
            'seo_title' => $this->string('seo_title'),
            'seo_description' => $this->string('seo_description'),
            'canonical_url' => $this->string('canonical_url'),
            'og_title' => $this->string('og_title'),
            'og_description' => $this->string('og_description'),
            'og_image' => $this->string('og_image'),
            'json_ld_placeholder' => $this->string('json_ld_placeholder'),
        ];
    }

    public function scheduleAt(): string
    {
        return $this->string('published_at');
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

    public function visibilityFilter(): string
    {
        return trim((string) $this->query('visibility', ''));
    }

    public function includeDeleted(): bool
    {
        return $this->boolean('include_deleted');
    }

    public function storyId(): int
    {
        return (int) $this->route('id', 0);
    }

    private function collectionIdInput(): ?int
    {
        $value = $this->input('collection_id', '');
        if ($value === '' || $value === null) {
            return null;
        }

        $collectionId = (int) $value;

        return $collectionId > 0 ? $collectionId : null;
    }

    private function normalizedTags(): string
    {
        $tags = array_map('trim', explode(',', $this->string('tags')));
        $tags = array_values(array_unique(array_filter($tags, static fn (string $tag): bool => $tag !== '')));

        return implode(', ', $tags);
    }
}
