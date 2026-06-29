<?php

declare(strict_types=1);

namespace App\Modules\Content\Services;

use App\Core\Modular\BaseModule;
use App\Core\Modular\BaseService;
use App\Modules\Content\Dto\ContentSearchCriteria;
use App\Modules\Content\Models\Content;
use App\Modules\Content\Models\ContentRepository;
use App\Modules\Content\Resources\ContentResource;
use RuntimeException;

final class ContentService extends BaseService
{
    public function __construct(
        BaseModule $module,
        private readonly ContentRepository $repository
    ) {
        parent::__construct($module);
    }

    public function creatorProfileForUser(int $userId): ?array
    {
        return $this->repository->findCreatorByUserId($userId);
    }

    public function create(int $userId, array $content): Content
    {
        $creator = $this->creatorProfileForUser($userId);
        if (!is_array($creator)) {
            throw new RuntimeException('Creator profile is required before creating content.');
        }

        $content = $this->prepareContent($content);

        if ($this->repository->slugExists((string) $content['slug'])) {
            throw new RuntimeException('Content slug is already in use.');
        }

        $contentId = $this->repository->create($content, (int) $creator['id']);

        return $this->requireContent($contentId, true);
    }

    public function update(int $contentId, array $content): Content
    {
        $content = $this->prepareContent($content);

        if ($this->repository->slugExists((string) $content['slug'], $contentId)) {
            throw new RuntimeException('Content slug is already in use.');
        }

        $this->repository->updateContent($contentId, $content);

        return $this->requireContent($contentId, true);
    }

    public function addPart(int $contentId, array $part): Content
    {
        $this->repository->addPart($contentId, $part);

        return $this->requireContent($contentId, true);
    }

    public function deletePart(int $contentId, int $partId): Content
    {
        $this->repository->deletePart($contentId, $partId);

        return $this->requireContent($contentId, true);
    }

    public function submitForReview(int $contentId): Content
    {
        $this->repository->submitForReview($contentId);

        return $this->requireContent($contentId, true);
    }

    public function review(int $contentId, array $review): Content
    {
        $this->repository->review($contentId, $review);

        return $this->requireContent($contentId, true);
    }

    public function delete(int $contentId): void
    {
        $this->repository->softDelete($contentId);
    }

    public function detailById(int $contentId, bool $includeDeleted = false): Content
    {
        return $this->requireContent($contentId, $includeDeleted);
    }

    public function detailBySlug(string $slug): Content
    {
        $content = $this->repository->findPublicBySlug($slug);
        if (!$content instanceof Content) {
            throw new RuntimeException('Content not found.');
        }

        $this->repository->incrementViews($content->id);

        return $this->requireContent($content->id, false);
    }

    public function paginate(ContentSearchCriteria $criteria): array
    {
        $result = $this->repository->paginate($criteria);
        $result['items'] = array_map(
            static fn (Content $content): array => ContentResource::listItem($content),
            (array) ($result['items'] ?? [])
        );

        return $result;
    }

    public function detailResource(Content $content): array
    {
        return ContentResource::detail($content);
    }

    public function statistics(Content $content): array
    {
        return ContentResource::statistics($content);
    }

    private function requireContent(int $contentId, bool $includeDeleted): Content
    {
        $content = $this->repository->findById($contentId, $includeDeleted);
        if (!$content instanceof Content) {
            throw new RuntimeException('Content not found.');
        }

        return $content;
    }

    private function prepareContent(array $content): array
    {
        $content['slug'] = trim((string) ($content['slug'] ?? ''));
        if ($content['slug'] === '') {
            $content['slug'] = strtolower(trim((string) ($content['title'] ?? '')));
        }

        $content['seo_title'] = trim((string) ($content['seo_title'] ?? ''));
        $content['seo_description'] = trim((string) ($content['seo_description'] ?? ''));

        if ($content['seo_title'] === '') {
            $content['seo_title'] = (string) ($content['title'] ?? '');
        }

        if ($content['seo_description'] === '') {
            $content['seo_description'] = (string) ($content['excerpt'] ?? '');
        }

        if ((string) ($content['access_policy'] ?? 'free') === 'free') {
            $content['price_minor'] = 0;
        }

        if ((string) ($content['currency_code'] ?? '') === '') {
            $content['currency_code'] = (string) $this->config('currency_code', 'IDR');
        }

        return $content;
    }
}
