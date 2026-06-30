<?php

declare(strict_types=1);

namespace App\Modules\Story\Models;

use App\Core\Modular\BaseRepository;
use App\Modules\Story\Dto\StoryListCriteria;
use PDO;

final class StoryRepository extends BaseRepository
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

    public function listCreatorCollections(int $creatorId): array
    {
        $statement = $this->prepare(
            'SELECT id, collection_code, title, slug, status, visibility
             FROM collections
             WHERE creator_id = :creator_id AND deleted_at IS NULL
             ORDER BY updated_at DESC, id DESC'
        );
        $statement->execute(['creator_id' => $creatorId]);
        $rows = $statement->fetchAll() ?: [];

        return array_map(static function (array $row): array {
            return [
                'id' => (int) ($row['id'] ?? 0),
                'collection_code' => (string) ($row['collection_code'] ?? ''),
                'title' => (string) ($row['title'] ?? ''),
                'slug' => (string) ($row['slug'] ?? ''),
                'status' => (string) ($row['status'] ?? ''),
                'visibility' => (string) ($row['visibility'] ?? ''),
            ];
        }, $rows);
    }

    public function findCollectionForCreator(int $creatorId, int $collectionId): ?array
    {
        $statement = $this->prepare(
            'SELECT id, creator_id, title, slug, status, visibility, deleted_at
             FROM collections
             WHERE id = :id AND creator_id = :creator_id
             LIMIT 1'
        );
        $statement->execute([
            'id' => $collectionId,
            'creator_id' => $creatorId,
        ]);
        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    public function create(array $story, int $creatorId): int
    {
        $statement = $this->prepare(
            'INSERT INTO stories (
                creator_id, collection_id, story_code, title, subtitle, slug, summary, body, cover, language, genre, tags,
                word_count, reading_time, status, visibility, seo_title, seo_description, canonical_url, og_title, og_description,
                og_image, json_ld_placeholder, published_at, deleted_at, created_at, updated_at
             ) VALUES (
                :creator_id, :collection_id, :story_code, :title, :subtitle, :slug, :summary, :body, :cover, :language, :genre, :tags,
                :word_count, :reading_time, :status, :visibility, :seo_title, :seo_description, :canonical_url, :og_title, :og_description,
                :og_image, :json_ld_placeholder, NULL, NULL, NOW(), NOW()
             )'
        );
        $statement->execute([
            'creator_id' => $creatorId,
            'collection_id' => $story['collection_id'],
            'story_code' => $this->temporaryStoryCode(),
            'title' => $story['title'],
            'subtitle' => $story['subtitle'],
            'slug' => $story['slug'],
            'summary' => $story['summary'],
            'body' => $story['body'],
            'cover' => $story['cover'],
            'language' => $story['language'],
            'genre' => $story['genre'],
            'tags' => $story['tags'],
            'word_count' => $story['word_count'],
            'reading_time' => $story['reading_time'],
            'status' => 'draft',
            'visibility' => $story['visibility'],
            'seo_title' => $story['seo_title'],
            'seo_description' => $story['seo_description'],
            'canonical_url' => $story['canonical_url'],
            'og_title' => $story['og_title'],
            'og_description' => $story['og_description'],
            'og_image' => $story['og_image'],
            'json_ld_placeholder' => $story['json_ld_placeholder'],
        ]);

        $storyId = $this->lastInsertId();
        $this->assignStoryCode($storyId);

        return $storyId;
    }

    public function findById(int $storyId, bool $includeDeleted = false): ?Story
    {
        $sql = $this->baseSelect() . ' WHERE s.id = :id';
        if (!$includeDeleted) {
            $sql .= ' AND s.deleted_at IS NULL';
        }

        $sql .= ' LIMIT 1';
        $statement = $this->prepare($sql);
        $statement->execute(['id' => $storyId]);
        $row = $statement->fetch();

        return is_array($row) ? Story::fromArray($row) : null;
    }

    public function findPublicBySlug(string $slug): ?Story
    {
        $statement = $this->prepare(
            $this->baseSelect() . ' WHERE s.slug = :slug
             AND ' . $this->publicAvailabilityCondition() . '
             LIMIT 1'
        );
        $statement->execute([
            'slug' => $slug,
            'published_status' => 'published',
            'scheduled_status' => 'scheduled',
            'public_visibility' => 'public',
        ]);
        $row = $statement->fetch();

        return is_array($row) ? Story::fromArray($row) : null;
    }

    public function slugExists(string $slug, ?int $ignoreStoryId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM stories WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($ignoreStoryId !== null) {
            $sql .= ' AND id != :ignore_id';
            $params['ignore_id'] = $ignoreStoryId;
        }

        $statement = $this->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }

    public function updateStory(int $storyId, array $story): void
    {
        $statement = $this->prepare(
            'UPDATE stories
             SET collection_id = :collection_id,
                 title = :title,
                 subtitle = :subtitle,
                 slug = :slug,
                 summary = :summary,
                 body = :body,
                 cover = :cover,
                 language = :language,
                 genre = :genre,
                 tags = :tags,
                 word_count = :word_count,
                 reading_time = :reading_time,
                 visibility = :visibility,
                 seo_title = :seo_title,
                 seo_description = :seo_description,
                 canonical_url = :canonical_url,
                 og_title = :og_title,
                 og_description = :og_description,
                 og_image = :og_image,
                 json_ld_placeholder = :json_ld_placeholder,
                 updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $storyId,
            'collection_id' => $story['collection_id'],
            'title' => $story['title'],
            'subtitle' => $story['subtitle'],
            'slug' => $story['slug'],
            'summary' => $story['summary'],
            'body' => $story['body'],
            'cover' => $story['cover'],
            'language' => $story['language'],
            'genre' => $story['genre'],
            'tags' => $story['tags'],
            'word_count' => $story['word_count'],
            'reading_time' => $story['reading_time'],
            'visibility' => $story['visibility'],
            'seo_title' => $story['seo_title'],
            'seo_description' => $story['seo_description'],
            'canonical_url' => $story['canonical_url'],
            'og_title' => $story['og_title'],
            'og_description' => $story['og_description'],
            'og_image' => $story['og_image'],
            'json_ld_placeholder' => $story['json_ld_placeholder'],
        ]);
    }

    public function setStatus(int $storyId, string $status, ?string $publishedAt = null): void
    {
        $statement = $this->prepare(
            'UPDATE stories
             SET status = :status,
                 published_at = :published_at,
                 updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $storyId,
            'status' => $status,
            'published_at' => $publishedAt,
        ]);
    }

    public function softDelete(int $storyId): void
    {
        $statement = $this->prepare(
            'UPDATE stories
             SET status = :status, deleted_at = NOW(), updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $storyId,
            'status' => 'archived',
        ]);
    }

    public function paginate(StoryListCriteria $criteria): array
    {
        $conditions = [];
        $params = [];

        if (!$criteria->includeDeleted) {
            $conditions[] = 's.deleted_at IS NULL';
        }

        if ($criteria->publicOnly) {
            $conditions[] = '(' . $this->publicAvailabilityCondition() . ')';
            $params['published_status'] = 'published';
            $params['scheduled_status'] = 'scheduled';
            $params['public_visibility'] = 'public';
        }

        if ($criteria->creatorId !== null) {
            $conditions[] = 's.creator_id = :creator_id';
            $params['creator_id'] = $criteria->creatorId;
        }

        if ($criteria->search !== '') {
            $conditions[] = '(s.title LIKE :search OR s.subtitle LIKE :search OR s.slug LIKE :search OR s.summary LIKE :search OR s.story_code LIKE :search OR s.genre LIKE :search OR s.tags LIKE :search)';
            $params['search'] = '%' . $criteria->search . '%';
        }

        if ($criteria->status !== '') {
            $conditions[] = 's.status = :status';
            $params['status'] = $criteria->status;
        }

        if ($criteria->visibility !== '') {
            $conditions[] = 's.visibility = :visibility';
            $params['visibility'] = $criteria->visibility;
        }

        $whereSql = $conditions === [] ? '' : ' WHERE ' . implode(' AND ', $conditions);

        $countStatement = $this->prepare(
            'SELECT COUNT(*)
             FROM stories s
             INNER JOIN creator_profiles cp ON cp.id = s.creator_id
             LEFT JOIN collections col ON col.id = s.collection_id' . $whereSql
        );
        foreach ($params as $key => $value) {
            $countStatement->bindValue(':' . $key, $value);
        }
        $countStatement->execute();
        $total = (int) $countStatement->fetchColumn();

        $statement = $this->prepare(
            $this->baseSelect() . $whereSql . '
             ORDER BY s.updated_at DESC, s.id DESC
             LIMIT :limit OFFSET :offset'
        );
        foreach ($params as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }
        $statement->bindValue(':limit', $criteria->perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $criteria->offset(), PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll() ?: [];
        $items = array_map(static fn (array $row): Story => Story::fromArray($row), $rows);

        return [
            'items' => $items,
            'total' => $total,
            'page' => $criteria->page,
            'per_page' => $criteria->perPage,
            'last_page' => max(1, (int) ceil($total / $criteria->perPage)),
        ];
    }

    private function assignStoryCode(int $storyId): void
    {
        $statement = $this->prepare(
            'UPDATE stories
             SET story_code = :story_code, updated_at = NOW()
             WHERE id = :id'
        );
        $statement->execute([
            'id' => $storyId,
            'story_code' => sprintf('STY-%06d', $storyId),
        ]);
    }

    private function temporaryStoryCode(): string
    {
        return 'TMP-' . bin2hex(random_bytes(6));
    }

    private function publicAvailabilityCondition(): string
    {
        return 's.deleted_at IS NULL
                AND s.visibility = :public_visibility
                AND (
                    s.status = :published_status
                    OR (
                        s.status = :scheduled_status
                        AND s.published_at IS NOT NULL
                        AND s.published_at <= NOW()
                    )
                )';
    }

    private function baseSelect(): string
    {
        return 'SELECT s.*, cp.display_name AS creator_display_name, cp.slug AS creator_slug, cp.creator_code,
                       col.title AS collection_title, col.slug AS collection_slug, col.collection_code
                FROM stories s
                INNER JOIN creator_profiles cp ON cp.id = s.creator_id
                LEFT JOIN collections col ON col.id = s.collection_id';
    }
}
