<?php

declare(strict_types=1);

namespace App\Modules\Collection\Models;

use App\Core\Modular\BaseRepository;
use App\Modules\Collection\Dto\CollectionListCriteria;
use PDO;
use RuntimeException;

final class CollectionRepository extends BaseRepository
{
    public function findCreatorByUserId(int $userId): ?array
    {
        $statement = $this->prepare(
            'SELECT id, creator_code, slug, display_name
             FROM creator_profiles
             WHERE user_id = :user_id AND deleted_at IS NULL
             LIMIT 1'
        );
        $statement->execute(['user_id' => $userId]);
        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    public function create(array $collection, int $creatorId): int
    {
        $statement = $this->prepare(
            'INSERT INTO collections (
                creator_id, collection_code, title, slug, description, cover_image_url, visibility, status,
                published_at, deleted_at, created_at, updated_at
             ) VALUES (
                :creator_id, :collection_code, :title, :slug, :description, :cover_image_url, :visibility, :status,
                NULL, NULL, NOW(), NOW()
             )'
        );
        $statement->execute([
            'creator_id' => $creatorId,
            'collection_code' => $this->temporaryCollectionCode(),
            'title' => $collection['title'],
            'slug' => $collection['slug'],
            'description' => $collection['description'],
            'cover_image_url' => $collection['cover_image_url'],
            'visibility' => $collection['visibility'],
            'status' => 'draft',
        ]);

        $collectionId = $this->lastInsertId();
        $this->assignCollectionCode($collectionId);

        return $collectionId;
    }

    public function findById(int $collectionId, bool $includeDeleted = false): ?Collection
    {
        $sql = $this->baseSelect() . ' WHERE col.id = :id';
        if (!$includeDeleted) {
            $sql .= ' AND col.deleted_at IS NULL';
        }

        $sql .= ' LIMIT 1';
        $statement = $this->prepare($sql);
        $statement->execute(['id' => $collectionId]);
        $row = $statement->fetch();
        if (!is_array($row)) {
            return null;
        }

        $collection = Collection::fromArray($row);
        $collection->items = $this->getItems($collectionId, false);

        return $collection;
    }

    public function findPublicBySlug(string $slug): ?Collection
    {
        $statement = $this->prepare(
            $this->baseSelect() . ' WHERE col.slug = :slug
             AND col.status = :status
             AND col.visibility = :visibility
             AND col.deleted_at IS NULL
             LIMIT 1'
        );
        $statement->execute([
            'slug' => $slug,
            'status' => 'published',
            'visibility' => 'public',
        ]);
        $row = $statement->fetch();
        if (!is_array($row)) {
            return null;
        }

        $collection = Collection::fromArray($row);
        $collection->items = $this->getItems($collection->id, true);

        return $collection;
    }

    public function slugExists(string $slug, ?int $ignoreCollectionId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM collections WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($ignoreCollectionId !== null) {
            $sql .= ' AND id != :ignore_id';
            $params['ignore_id'] = $ignoreCollectionId;
        }

        $statement = $this->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }

    public function updateCollection(int $collectionId, array $collection): void
    {
        $statement = $this->prepare(
            'UPDATE collections
             SET title = :title,
                 slug = :slug,
                 description = :description,
                 cover_image_url = :cover_image_url,
                 visibility = :visibility,
                 updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $collectionId,
            'title' => $collection['title'],
            'slug' => $collection['slug'],
            'description' => $collection['description'],
            'cover_image_url' => $collection['cover_image_url'],
            'visibility' => $collection['visibility'],
        ]);
    }

    public function setStatus(int $collectionId, string $status): void
    {
        $statement = $this->prepare(
            'UPDATE collections
             SET status = :status,
                 published_at = CASE
                     WHEN :status = :published_status THEN COALESCE(published_at, NOW())
                     ELSE NULL
                 END,
                 updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $collectionId,
            'status' => $status,
            'published_status' => 'published',
        ]);
    }

    public function softDelete(int $collectionId): void
    {
        $pdo = $this->pdo();
        $pdo->beginTransaction();

        try {
            $statement = $this->prepare(
                'UPDATE collections
                 SET status = :status, deleted_at = NOW(), updated_at = NOW()
                 WHERE id = :id AND deleted_at IS NULL'
            );
            $statement->execute([
                'id' => $collectionId,
                'status' => 'removed',
            ]);

            $itemStatement = $this->prepare(
                'UPDATE collection_items
                 SET deleted_at = NOW(), updated_at = NOW()
                 WHERE collection_id = :collection_id AND deleted_at IS NULL'
            );
            $itemStatement->execute(['collection_id' => $collectionId]);

            $pdo->commit();
        } catch (\Throwable $throwable) {
            $pdo->rollBack();

            throw new RuntimeException('Failed to delete collection.', 0, $throwable);
        }
    }

    public function activeItemExists(int $collectionId, int $contentId): bool
    {
        $statement = $this->prepare(
            'SELECT COUNT(*)
             FROM collection_items
             WHERE collection_id = :collection_id
               AND content_id = :content_id
               AND deleted_at IS NULL'
        );
        $statement->execute([
            'collection_id' => $collectionId,
            'content_id' => $contentId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function addItem(int $collectionId, int $contentId): void
    {
        $statement = $this->prepare(
            'INSERT INTO collection_items (
                collection_id, content_id, sort_order, deleted_at, created_at, updated_at
             ) VALUES (
                :collection_id, :content_id, :sort_order, NULL, NOW(), NOW()
             )'
        );
        $statement->execute([
            'collection_id' => $collectionId,
            'content_id' => $contentId,
            'sort_order' => $this->nextSortOrder($collectionId),
        ]);
    }

    public function deleteItem(int $collectionId, int $itemId): void
    {
        $statement = $this->prepare(
            'UPDATE collection_items
             SET deleted_at = NOW(), updated_at = NOW()
             WHERE id = :id AND collection_id = :collection_id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $itemId,
            'collection_id' => $collectionId,
        ]);
    }

    public function reorderItems(int $collectionId, array $itemOrders): void
    {
        if ($itemOrders === []) {
            return;
        }

        $pdo = $this->pdo();
        $pdo->beginTransaction();

        try {
            $statement = $this->prepare(
                'UPDATE collection_items
                 SET sort_order = :sort_order, updated_at = NOW()
                 WHERE id = :id AND collection_id = :collection_id AND deleted_at IS NULL'
            );

            foreach ($itemOrders as $itemId => $sortOrder) {
                $statement->execute([
                    'id' => (int) $itemId,
                    'collection_id' => $collectionId,
                    'sort_order' => (int) $sortOrder,
                ]);
            }

            $pdo->commit();
        } catch (\Throwable $throwable) {
            $pdo->rollBack();

            throw new RuntimeException('Failed to reorder collection items.', 0, $throwable);
        }
    }

    public function paginate(CollectionListCriteria $criteria): array
    {
        $conditions = [];
        $params = [];

        if (!$criteria->includeDeleted) {
            $conditions[] = 'col.deleted_at IS NULL';
        }

        if ($criteria->publicOnly) {
            $conditions[] = 'col.status = :public_status';
            $conditions[] = 'col.visibility = :public_visibility';
            $params['public_status'] = 'published';
            $params['public_visibility'] = 'public';
        }

        if ($criteria->creatorId !== null) {
            $conditions[] = 'col.creator_id = :creator_id';
            $params['creator_id'] = $criteria->creatorId;
        }

        if ($criteria->status !== '') {
            $conditions[] = 'col.status = :status';
            $params['status'] = $criteria->status;
        }

        if ($criteria->visibility !== '') {
            $conditions[] = 'col.visibility = :visibility';
            $params['visibility'] = $criteria->visibility;
        }

        $whereSql = $conditions === [] ? '' : ' WHERE ' . implode(' AND ', $conditions);

        $countStatement = $this->prepare(
            'SELECT COUNT(*)
             FROM collections col
             INNER JOIN creator_profiles cp ON cp.id = col.creator_id' . $whereSql
        );
        foreach ($params as $key => $value) {
            $countStatement->bindValue(':' . $key, $value);
        }
        $countStatement->execute();
        $total = (int) $countStatement->fetchColumn();

        $statement = $this->prepare(
            $this->baseSelect() . $whereSql . '
             ORDER BY col.updated_at DESC, col.id DESC
             LIMIT :limit OFFSET :offset'
        );
        foreach ($params as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }
        $statement->bindValue(':limit', $criteria->perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $criteria->offset(), PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll() ?: [];
        $items = array_map(function (array $row) use ($criteria): Collection {
            $collection = Collection::fromArray($row);
            $collection->items = $this->getItems($collection->id, $criteria->publicOnly);

            return $collection;
        }, $rows);

        return [
            'items' => $items,
            'total' => $total,
            'page' => $criteria->page,
            'per_page' => $criteria->perPage,
            'last_page' => max(1, (int) ceil($total / $criteria->perPage)),
        ];
    }

    public function getItems(int $collectionId, bool $publicOnly): array
    {
        $sql = 'SELECT ci.*, c.content_code, c.title AS content_title, c.slug AS content_slug,
                       c.excerpt AS content_excerpt, c.cover_image_url, c.status AS content_status, c.visibility AS content_visibility
                FROM collection_items ci
                INNER JOIN contents c ON c.id = ci.content_id
                WHERE ci.collection_id = :collection_id
                  AND ci.deleted_at IS NULL
                  AND c.deleted_at IS NULL';

        if ($publicOnly) {
            $sql .= ' AND c.status = :content_status AND c.visibility = :content_visibility';
        }

        $sql .= ' ORDER BY ci.sort_order ASC, ci.id ASC';
        $statement = $this->prepare($sql);
        $statement->bindValue(':collection_id', $collectionId, PDO::PARAM_INT);
        if ($publicOnly) {
            $statement->bindValue(':content_status', 'published');
            $statement->bindValue(':content_visibility', 'public');
        }
        $statement->execute();
        $rows = $statement->fetchAll() ?: [];

        return array_map(static fn (array $row): CollectionItem => CollectionItem::fromArray($row), $rows);
    }

    public function listCreatorContents(int $creatorId): array
    {
        $statement = $this->prepare(
            'SELECT id, content_code, title, slug, status
             FROM contents
             WHERE creator_id = :creator_id AND deleted_at IS NULL
             ORDER BY updated_at DESC, id DESC'
        );
        $statement->execute(['creator_id' => $creatorId]);
        $rows = $statement->fetchAll() ?: [];

        return array_map(static function (array $row): array {
            return [
                'id' => (int) ($row['id'] ?? 0),
                'content_code' => (string) ($row['content_code'] ?? ''),
                'title' => (string) ($row['title'] ?? ''),
                'slug' => (string) ($row['slug'] ?? ''),
                'status' => (string) ($row['status'] ?? ''),
            ];
        }, $rows);
    }

    public function findContentForCreator(int $creatorId, int $contentId): ?array
    {
        $statement = $this->prepare(
            'SELECT id, creator_id, title, slug, status, visibility, deleted_at
             FROM contents
             WHERE id = :id AND creator_id = :creator_id
             LIMIT 1'
        );
        $statement->execute([
            'id' => $contentId,
            'creator_id' => $creatorId,
        ]);
        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    private function assignCollectionCode(int $collectionId): void
    {
        $statement = $this->prepare(
            'UPDATE collections
             SET collection_code = :collection_code, updated_at = NOW()
             WHERE id = :id'
        );
        $statement->execute([
            'id' => $collectionId,
            'collection_code' => sprintf('COL-%06d', $collectionId),
        ]);
    }

    private function temporaryCollectionCode(): string
    {
        return 'TMP-' . bin2hex(random_bytes(6));
    }

    private function nextSortOrder(int $collectionId): int
    {
        $statement = $this->prepare(
            'SELECT COALESCE(MAX(sort_order), 0) + 1
             FROM collection_items
             WHERE collection_id = :collection_id AND deleted_at IS NULL'
        );
        $statement->execute(['collection_id' => $collectionId]);

        return (int) $statement->fetchColumn();
    }

    private function baseSelect(): string
    {
        return 'SELECT col.*, cp.display_name AS creator_display_name, cp.slug AS creator_slug, cp.creator_code
                FROM collections col
                INNER JOIN creator_profiles cp ON cp.id = col.creator_id';
    }
}
