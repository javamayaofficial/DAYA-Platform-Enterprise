<?php

declare(strict_types=1);

namespace App\Modules\Story\Services;

use App\Core\Modular\BaseModule;
use App\Core\Modular\BaseService;
use App\Modules\Story\Dto\StoryListCriteria;
use App\Modules\Story\Models\Story;
use App\Modules\Story\Models\StoryRepository;
use App\Modules\Story\Resources\StoryResource;
use RuntimeException;

final class StoryService extends BaseService
{
    public function __construct(
        BaseModule $module,
        private readonly StoryRepository $repository,
        private readonly StoryValidator $validator
    ) {
        parent::__construct($module);
    }

    public function creatorProfileForUser(int $userId): ?array
    {
        return $this->repository->findCreatorByUserId($userId);
    }

    public function create(int $userId, array $story): Story
    {
        $creator = $this->requireCreatorProfile($userId);
        $story = $this->prepareStory($story);
        $this->ensureCollectionOwnership((int) $creator['id'], $story['collection_id']);

        if ($this->repository->slugExists((string) $story['slug'])) {
            throw new RuntimeException('Story slug is already in use.');
        }

        $storyId = $this->repository->create($story, (int) $creator['id']);

        return $this->requireStory($storyId, true);
    }

    public function update(int $storyId, array $story): Story
    {
        $existing = $this->requireStory($storyId, true);
        if ($existing->deletedAt !== null) {
            throw new RuntimeException('Deleted story cannot be updated.');
        }

        $story = $this->prepareStory($story, $existing);
        $this->ensureCollectionOwnership($existing->creatorId, $story['collection_id']);

        if ($this->repository->slugExists((string) $story['slug'], $storyId)) {
            throw new RuntimeException('Story slug is already in use.');
        }

        $this->repository->updateStory($storyId, $story);

        return $this->requireStory($storyId, true);
    }

    public function markReview(int $storyId): Story
    {
        $story = $this->requireStory($storyId, true);
        if ($story->deletedAt !== null) {
            throw new RuntimeException('Deleted story cannot change status.');
        }

        $this->repository->setStatus($storyId, 'review', null);

        return $this->requireStory($storyId, true);
    }

    public function publish(int $storyId): Story
    {
        $story = $this->requireStory($storyId, true);
        if ($story->deletedAt !== null) {
            throw new RuntimeException('Deleted story cannot change status.');
        }

        $this->repository->setStatus($storyId, 'published', date('Y-m-d H:i:s'));

        return $this->requireStory($storyId, true);
    }

    public function schedule(int $storyId, string $scheduleAt): Story
    {
        $story = $this->requireStory($storyId, true);
        if ($story->deletedAt !== null) {
            throw new RuntimeException('Deleted story cannot change status.');
        }

        $validation = $this->validator->validateScheduleAt($scheduleAt);
        if (($validation['errors'] ?? []) !== []) {
            throw new RuntimeException((string) (($validation['errors']['published_at'] ?? 'Schedule date is not valid.')));
        }

        $normalized = $this->normalizeDateTime($scheduleAt);
        if ($normalized === null || strtotime($normalized) === false || strtotime($normalized) <= time()) {
            throw new RuntimeException('Schedule date must be in the future.');
        }

        $this->repository->setStatus($storyId, 'scheduled', $normalized);

        return $this->requireStory($storyId, true);
    }

    public function archive(int $storyId): Story
    {
        $story = $this->requireStory($storyId, true);
        if ($story->deletedAt !== null) {
            throw new RuntimeException('Deleted story cannot change status.');
        }

        $this->repository->setStatus($storyId, 'archived', $story->publishedAt);

        return $this->requireStory($storyId, true);
    }

    public function delete(int $storyId): void
    {
        $story = $this->requireStory($storyId, true);
        if ($story->deletedAt !== null) {
            throw new RuntimeException('Story already deleted.');
        }

        $this->repository->softDelete($storyId);
    }

    public function duplicate(int $storyId): Story
    {
        $existing = $this->requireStory($storyId, true);
        $duplicateData = [
            'collection_id' => $existing->collectionId,
            'title' => $existing->title . ' Copy',
            'subtitle' => $existing->subtitle,
            'slug' => $this->uniqueDuplicateSlug($existing->slug),
            'summary' => $existing->summary,
            'body' => $existing->body,
            'cover' => $existing->cover,
            'language' => $existing->language,
            'genre' => $existing->genre,
            'tags' => $existing->tags,
            'visibility' => $existing->visibility,
            'seo_title' => $existing->seoTitle,
            'seo_description' => $existing->seoDescription,
            'canonical_url' => '',
            'og_title' => $existing->ogTitle,
            'og_description' => $existing->ogDescription,
            'og_image' => $existing->ogImage,
            'json_ld_placeholder' => '',
        ];
        $duplicateData = $this->prepareStory($duplicateData);

        $duplicateId = $this->repository->create($duplicateData, $existing->creatorId);

        return $this->requireStory($duplicateId, true);
    }

    public function detailById(int $storyId, bool $includeDeleted = false): Story
    {
        return $this->requireStory($storyId, $includeDeleted);
    }

    public function detailBySlug(string $slug): Story
    {
        $story = $this->repository->findPublicBySlug($slug);
        if (!$story instanceof Story) {
            throw new RuntimeException('Story not found.');
        }

        return $story;
    }

    public function paginate(StoryListCriteria $criteria): array
    {
        $result = $this->repository->paginate($criteria);
        $result['items'] = array_map(
            static fn (Story $story): array => StoryResource::listItem($story),
            (array) ($result['items'] ?? [])
        );

        return $result;
    }

    public function detailResource(Story $story): array
    {
        return StoryResource::detail($story);
    }

    public function statistics(Story $story): array
    {
        return StoryResource::statistics($story);
    }

    public function creatorCollections(int $creatorId): array
    {
        return $this->repository->listCreatorCollections($creatorId);
    }

    private function requireStory(int $storyId, bool $includeDeleted): Story
    {
        $story = $this->repository->findById($storyId, $includeDeleted);
        if (!$story instanceof Story) {
            throw new RuntimeException('Story not found.');
        }

        return $story;
    }

    private function requireCreatorProfile(int $userId): array
    {
        $creator = $this->creatorProfileForUser($userId);
        if (!is_array($creator)) {
            throw new RuntimeException('Creator profile is required before creating story.');
        }

        return $creator;
    }

    private function ensureCollectionOwnership(int $creatorId, ?int $collectionId): void
    {
        if ($collectionId === null) {
            return;
        }

        $collection = $this->repository->findCollectionForCreator($creatorId, $collectionId);
        if (!is_array($collection) || ($collection['deleted_at'] ?? null) !== null) {
            throw new RuntimeException('Collection not found for this creator.');
        }
    }

    private function prepareStory(array $story, ?Story $existing = null): array
    {
        $title = trim((string) ($story['title'] ?? ''));
        $subtitle = trim((string) ($story['subtitle'] ?? ''));
        $summary = trim((string) ($story['summary'] ?? ''));
        $body = trim((string) ($story['body'] ?? ''));
        $slug = trim((string) ($story['slug'] ?? ''));
        $slugSource = $slug !== '' ? $slug : $title;
        $slug = strtolower((string) preg_replace('/[^a-z0-9]+/', '-', $slugSource));
        $slug = trim($slug, '-');

        $wordCount = $this->wordCount($body);
        $readingTime = $wordCount === 0 ? 0 : max(1, (int) ceil($wordCount / max(1, (int) $this->config('words_per_minute', 200))));

        $story['collection_id'] = $story['collection_id'] ?? $existing?->collectionId;
        $story['title'] = $title;
        $story['subtitle'] = $subtitle;
        $story['slug'] = $slug;
        $story['summary'] = $summary;
        $story['body'] = $body;
        $story['cover'] = trim((string) ($story['cover'] ?? ''));
        $story['language'] = trim((string) ($story['language'] ?? $this->config('default_language', 'id')));
        $story['genre'] = trim((string) ($story['genre'] ?? ''));
        $story['tags'] = trim((string) ($story['tags'] ?? ''));
        $story['reading_time'] = $readingTime;
        $story['visibility'] = trim((string) ($story['visibility'] ?? $this->config('default_visibility', 'private')));
        $story['seo_title'] = trim((string) ($story['seo_title'] ?? ''));
        $story['seo_description'] = trim((string) ($story['seo_description'] ?? ''));
        $story['canonical_url'] = trim((string) ($story['canonical_url'] ?? ''));
        $story['og_title'] = trim((string) ($story['og_title'] ?? ''));
        $story['og_description'] = trim((string) ($story['og_description'] ?? ''));
        $story['og_image'] = trim((string) ($story['og_image'] ?? ''));
        $story['json_ld_placeholder'] = trim((string) ($story['json_ld_placeholder'] ?? ''));

        if ($story['seo_title'] === '') {
            $story['seo_title'] = $title;
        }

        if ($story['seo_description'] === '') {
            $story['seo_description'] = $summary;
        }

        if ($story['canonical_url'] === '') {
            $story['canonical_url'] = '/stories/' . $slug;
        }

        if ($story['og_title'] === '') {
            $story['og_title'] = $story['seo_title'];
        }

        if ($story['og_description'] === '') {
            $story['og_description'] = $story['seo_description'];
        }

        if ($story['og_image'] === '') {
            $story['og_image'] = $story['cover'];
        }

        if ($story['json_ld_placeholder'] === '') {
            $story['json_ld_placeholder'] = $this->defaultJsonLdPlaceholder($story);
        }

        return $story;
    }

    private function defaultJsonLdPlaceholder(array $story): string
    {
        $payload = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'headline' => (string) ($story['title'] ?? ''),
            'description' => (string) ($story['summary'] ?? ''),
            'inLanguage' => (string) ($story['language'] ?? ''),
            'url' => (string) ($story['canonical_url'] ?? ''),
        ];

        return (string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    private function uniqueDuplicateSlug(string $baseSlug): string
    {
        $candidate = $baseSlug . '-copy';
        $counter = 2;

        while ($this->repository->slugExists($candidate)) {
            $candidate = $baseSlug . '-copy-' . $counter;
            $counter++;
        }

        return $candidate;
    }

    private function wordCount(string $body): int
    {
        $matches = [];
        preg_match_all('/\p{L}[\p{L}\p{N}\-]*/u', strip_tags($body), $matches);

        return count($matches[0] ?? []);
    }

    private function normalizeDateTime(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $timestamp);
    }
}
