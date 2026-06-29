<?php

declare(strict_types=1);

namespace App\Modules\Collection\Services;

use App\Core\Modular\BaseModule;
use App\Core\Modular\BaseService;
use App\Modules\Collection\Dto\CollectionListCriteria;
use App\Modules\Collection\Models\Collection;
use App\Modules\Collection\Models\CollectionRepository;
use App\Modules\Collection\Resources\CollectionResource;
use RuntimeException;

final class CollectionService extends BaseService
{
    public function __construct(
        BaseModule $module,
        private readonly CollectionRepository $repository,
        private readonly CollectionValidator $validator
    ) {
        parent::__construct($module);
    }

    public function creatorProfileForUser(int $userId): ?array
    {
        return $this->repository->findCreatorByUserId($userId);
    }

    public function create(int $userId, array $collection): Collection
    {
        $creator = $this->requireCreatorProfile($userId);
        $collection = $this->prepareCollection($collection);

        if ($this->repository->slugExists((string) $collection['slug'])) {
            throw new RuntimeException('Collection slug is already in use.');
        }

        $collectionId = $this->repository->create($collection, (int) $creator['id']);

        return $this->requireCollection($collectionId, true);
    }

    public function update(int $collectionId, array $collection): Collection
    {
        $existing = $this->requireCollection($collectionId, true);
        if ($existing->deletedAt !== null) {
            throw new RuntimeException('Deleted collection cannot be updated.');
        }

        $collection = $this->prepareCollection($collection);
        if ($this->repository->slugExists((string) $collection['slug'], $collectionId)) {
            throw new RuntimeException('Collection slug is already in use.');
        }

        $this->repository->updateCollection($collectionId, $collection);

        return $this->requireCollection($collectionId, true);
    }

    public function changeStatus(int $collectionId, string $status): Collection
    {
        $collection = $this->requireCollection($collectionId, true);
        if ($collection->deletedAt !== null) {
            throw new RuntimeException('Deleted collection cannot change status.');
        }

        $publishableStatuses = array_map(
            'strval',
            (array) $this->config('publishable_statuses', ['draft', 'published'])
        );

        if (!in_array($status, $publishableStatuses, true)) {
            throw new RuntimeException('Collection status transition is not allowed.');
        }

        $validation = $this->validator->validateStatus($status);
        if (($validation['errors'] ?? []) !== []) {
            throw new RuntimeException((string) (($validation['errors']['status'] ?? 'Collection status is not valid.')));
        }

        $this->repository->setStatus($collectionId, $status);

        return $this->requireCollection($collectionId, true);
    }

    public function delete(int $collectionId): void
    {
        $collection = $this->requireCollection($collectionId, true);
        if ($collection->deletedAt !== null) {
            throw new RuntimeException('Collection already deleted.');
        }

        $this->repository->softDelete($collectionId);
    }

    public function addItem(int $collectionId, int $creatorId, int $contentId): Collection
    {
        $collection = $this->requireCollection($collectionId, true);
        if ($collection->deletedAt !== null) {
            throw new RuntimeException('Deleted collection cannot be modified.');
        }

        $validation = $this->validator->validateItemContentId($contentId);
        if (($validation['errors'] ?? []) !== []) {
            throw new RuntimeException((string) (($validation['errors']['content_id'] ?? 'Content is required.')));
        }

        $content = $this->repository->findContentForCreator($creatorId, $contentId);
        if (!is_array($content) || ($content['deleted_at'] ?? null) !== null) {
            throw new RuntimeException('Content not found for this creator.');
        }

        if ($this->repository->activeItemExists($collectionId, $contentId)) {
            throw new RuntimeException('Content already exists in this collection.');
        }

        $this->repository->addItem($collectionId, $contentId);

        return $this->requireCollection($collectionId, true);
    }

    public function removeItem(int $collectionId, int $itemId): Collection
    {
        $collection = $this->requireCollection($collectionId, true);
        if ($collection->deletedAt !== null) {
            throw new RuntimeException('Deleted collection cannot be modified.');
        }

        $knownItemIds = array_map(
            static fn ($item): int => (int) $item->id,
            $collection->items
        );

        if (!in_array($itemId, $knownItemIds, true)) {
            throw new RuntimeException('Collection item not found.');
        }

        $this->repository->deleteItem($collectionId, $itemId);

        return $this->requireCollection($collectionId, true);
    }

    public function reorderItems(int $collectionId, array $itemOrders): Collection
    {
        $collection = $this->requireCollection($collectionId, true);
        if ($collection->deletedAt !== null) {
            throw new RuntimeException('Deleted collection cannot be modified.');
        }

        $validation = $this->validator->validateItemOrders($itemOrders);
        if (($validation['errors'] ?? []) !== []) {
            throw new RuntimeException((string) (($validation['errors']['item_orders'] ?? 'Collection item order is not valid.')));
        }

        $activeItemIds = array_map(
            static fn ($item): int => (int) $item->id,
            $collection->items
        );
        $providedIds = array_map('intval', array_keys($itemOrders));
        sort($activeItemIds);
        sort($providedIds);

        if ($activeItemIds !== $providedIds) {
            throw new RuntimeException('Collection reorder payload must include all active items.');
        }

        $this->repository->reorderItems($collectionId, $itemOrders);

        return $this->requireCollection($collectionId, true);
    }

    public function detailById(int $collectionId, bool $includeDeleted = false): Collection
    {
        return $this->requireCollection($collectionId, $includeDeleted);
    }

    public function detailBySlug(string $slug): Collection
    {
        $collection = $this->repository->findPublicBySlug($slug);
        if (!$collection instanceof Collection) {
            throw new RuntimeException('Collection not found.');
        }

        return $collection;
    }

    public function paginate(CollectionListCriteria $criteria): array
    {
        $result = $this->repository->paginate($criteria);
        $result['items'] = array_map(
            static fn (Collection $collection): array => CollectionResource::listItem($collection),
            (array) ($result['items'] ?? [])
        );

        return $result;
    }

    public function detailResource(Collection $collection): array
    {
        return CollectionResource::detail($collection);
    }

    public function statistics(Collection $collection): array
    {
        return CollectionResource::statistics($collection);
    }

    public function creatorContents(int $creatorId): array
    {
        return $this->repository->listCreatorContents($creatorId);
    }

    private function requireCollection(int $collectionId, bool $includeDeleted): Collection
    {
        $collection = $this->repository->findById($collectionId, $includeDeleted);
        if (!$collection instanceof Collection) {
            throw new RuntimeException('Collection not found.');
        }

        return $collection;
    }

    private function requireCreatorProfile(int $userId): array
    {
        $creator = $this->creatorProfileForUser($userId);
        if (!is_array($creator)) {
            throw new RuntimeException('Creator profile is required before creating collection.');
        }

        return $creator;
    }

    private function prepareCollection(array $collection): array
    {
        $title = trim((string) ($collection['title'] ?? ''));
        $slug = trim((string) ($collection['slug'] ?? ''));
        $slugSource = $slug !== '' ? $slug : $title;
        $slug = strtolower((string) preg_replace('/[^a-z0-9]+/', '-', $slugSource));
        $slug = trim($slug, '-');

        $collection['title'] = $title;
        $collection['slug'] = $slug;
        $collection['description'] = trim((string) ($collection['description'] ?? ''));
        $collection['cover_image_url'] = trim((string) ($collection['cover_image_url'] ?? ''));
        $collection['visibility'] = trim((string) ($collection['visibility'] ?? $this->config('default_visibility', 'public')));

        return $collection;
    }
}
