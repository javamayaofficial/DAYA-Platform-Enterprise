<?php

declare(strict_types=1);

namespace App\Modules\Content\Models;

use App\Core\Modular\BaseRepository;
use App\Modules\Content\Dto\ContentSearchCriteria;
use RuntimeException;

final class ContentRepository extends BaseRepository
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

    public function create(array $content, int $creatorId): int
    {
        $statement = $this->prepare(
            'INSERT INTO contents (
                creator_id, content_code, content_type, title, slug, excerpt, body, cover_image_url,
                seo_title, seo_description, access_policy, price_minor, currency_code, visibility, status,
                views_count, likes_count, comments_count, shares_count, sponsor_count, donation_count,
                affiliate_count, revenue_minor, recommendation_score, published_at, reviewed_at,
                reviewed_by_user_id, review_notes, deleted_at, created_at, updated_at
             ) VALUES (
                :creator_id, :content_code, :content_type, :title, :slug, :excerpt, :body, :cover_image_url,
                :seo_title, :seo_description, :access_policy, :price_minor, :currency_code, :visibility, :status,
                0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL,
                NULL, '', NULL, NOW(), NOW()
             )'
        );
        $statement->execute([
            'creator_id' => $creatorId,
            'content_code' => $this->temporaryContentCode(),
            'content_type' => $content['content_type'],
            'title' => $content['title'],
            'slug' => $content['slug'],
            'excerpt' => $content['excerpt'],
            'body' => $content['body'],
            'cover_image_url' => $content['cover_image_url'],
            'seo_title' => $content['seo_title'],
            'seo_description' => $content['seo_description'],
            'access_policy' => $content['access_policy'],
            'price_minor' => $content['price_minor'],
            'currency_code' => $content['currency_code'],
            'visibility' => $content['visibility'],
            'status' => 'draft',
        ]);

        $contentId = $this->lastInsertId();
        $this->assignContentCode($contentId);

        return $contentId;
    }

    public function findById(int $contentId, bool $includeDeleted = false): ?Content
    {
        $sql = $this->baseSelect() . ' WHERE c.id = :id';
        if (!$includeDeleted) {
            $sql .= ' AND c.deleted_at IS NULL';
        }

        $sql .= ' LIMIT 1';
        $statement = $this->prepare($sql);
        $statement->execute(['id' => $contentId]);
        $row = $statement->fetch();
        if (!is_array($row)) {
            return null;
        }

        $content = Content::fromArray($row);
        $content->parts = $this->getParts($contentId);

        return $content;
    }

    public function findPublicBySlug(string $slug): ?Content
    {
        $statement = $this->prepare(
            $this->baseSelect() . ' WHERE c.slug = :slug
             AND c.status = :status
             AND c.visibility = :visibility
             AND c.deleted_at IS NULL
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

        $content = Content::fromArray($row);
        $content->parts = $this->getPublicParts($content->id);

        return $content;
    }

    public function slugExists(string $slug, ?int $ignoreContentId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM contents WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($ignoreContentId !== null) {
            $sql .= ' AND id != :ignore_id';
            $params['ignore_id'] = $ignoreContentId;
        }

        $statement = $this->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }

    public function updateContent(int $contentId, array $content): void
    {
        $statement = $this->prepare(
            'UPDATE contents
             SET content_type = :content_type,
                 title = :title,
                 slug = :slug,
                 excerpt = :excerpt,
                 body = :body,
                 cover_image_url = :cover_image_url,
                 seo_title = :seo_title,
                 seo_description = :seo_description,
                 access_policy = :access_policy,
                 price_minor = :price_minor,
                 currency_code = :currency_code,
                 visibility = :visibility,
                 status = CASE
                     WHEN status = :published_status THEN :updated_status
                     ELSE status
                 END,
                 published_at = CASE
                     WHEN status = :published_status THEN published_at
                     ELSE published_at
                 END,
                 updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $contentId,
            'content_type' => $content['content_type'],
            'title' => $content['title'],
            'slug' => $content['slug'],
            'excerpt' => $content['excerpt'],
            'body' => $content['body'],
            'cover_image_url' => $content['cover_image_url'],
            'seo_title' => $content['seo_title'],
            'seo_description' => $content['seo_description'],
            'access_policy' => $content['access_policy'],
            'price_minor' => $content['price_minor'],
            'currency_code' => $content['currency_code'],
            'visibility' => $content['visibility'],
            'published_status' => 'published',
            'updated_status' => 'updated',
        ]);
    }

    public function addPart(int $contentId, array $part): void
    {
        $statement = $this->prepare(
            'INSERT INTO content_parts (
                content_id, title, summary, body, media_url, is_free_preview, sort_order, release_at, deleted_at, created_at, updated_at
             ) VALUES (
                :content_id, :title, :summary, :body, :media_url, :is_free_preview, :sort_order, :release_at, NULL, NOW(), NOW()
             )'
        );
        $statement->execute([
            'content_id' => $contentId,
            'title' => $part['title'],
            'summary' => $part['summary'],
            'body' => $part['body'],
            'media_url' => $part['media_url'],
            'is_free_preview' => $part['is_free_preview'] ? 1 : 0,
            'sort_order' => $this->nextSortOrder($contentId),
            'release_at' => $part['release_at'] !== '' ? $part['release_at'] : null,
        ]);
    }

    public function deletePart(int $contentId, int $partId): void
    {
        $statement = $this->prepare(
            'UPDATE content_parts
             SET deleted_at = NOW(), updated_at = NOW()
             WHERE id = :id AND content_id = :content_id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $partId,
            'content_id' => $contentId,
        ]);
    }

    public function review(int $contentId, array $review): void
    {
        $status = $review['status'];
        $statement = $this->prepare(
            'UPDATE contents
             SET status = :status,
                 published_at = CASE
                     WHEN :status = :published_status THEN COALESCE(published_at, NOW())
                     ELSE published_at
                 END,
                 reviewed_at = NOW(),
                 reviewed_by_user_id = :reviewed_by_user_id,
                 review_notes = :review_notes,
                 updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $contentId,
            'status' => $status,
            'published_status' => 'published',
            'reviewed_by_user_id' => (int) $review['reviewed_by_user_id'],
            'review_notes' => $review['review_notes'],
        ]);
    }

    public function submitForReview(int $contentId): void
    {
        $statement = $this->prepare(
            'UPDATE contents
             SET status = :status, updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $contentId,
            'status' => 'in_review',
        ]);
    }

    public function softDelete(int $contentId): void
    {
        $statement = $this->prepare(
            'UPDATE contents
             SET status = :status, deleted_at = NOW(), updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $contentId,
            'status' => 'removed',
        ]);
    }

    public function incrementViews(int $contentId): void
    {
        $statement = $this->prepare(
            'UPDATE contents
             SET views_count = views_count + 1, updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute(['id' => $contentId]);
    }

    public function paginate(ContentSearchCriteria $criteria): array
    {
        $conditions = [];
        $params = [];

        if (!$criteria->includeDeleted) {
            $conditions[] = 'c.deleted_at IS NULL';
        }

        if ($criteria->publicOnly) {
            $conditions[] = 'c.status = :public_status';
            $conditions[] = 'c.visibility = :public_visibility';
            $params['public_status'] = 'published';
            $params['public_visibility'] = 'public';
        }

        if ($criteria->creatorId !== null) {
            $conditions[] = 'c.creator_id = :creator_id';
            $params['creator_id'] = $criteria->creatorId;
        }

        if ($criteria->search !== '') {
            $conditions[] = '(c.title LIKE :search OR c.slug LIKE :search OR c.excerpt LIKE :search OR c.content_code LIKE :search)';
            $params['search'] = '%' . $criteria->search . '%';
        }

        if ($criteria->status !== '') {
            $conditions[] = 'c.status = :status';
            $params['status'] = $criteria->status;
        }

        if ($criteria->contentType !== '') {
            $conditions[] = 'c.content_type = :content_type';
            $params['content_type'] = $criteria->contentType;
        }

        $whereSql = $conditions === [] ? '' : ' WHERE ' . implode(' AND ', $conditions);

        $countStatement = $this->prepare(
            'SELECT COUNT(*) FROM contents c
             INNER JOIN creator_profiles cp ON cp.id = c.creator_id' . $whereSql
        );
        foreach ($params as $key => $value) {
            $countStatement->bindValue(':' . $key, $value);
        }
        $countStatement->execute();
        $total = (int) $countStatement->fetchColumn();

        $statement = $this->prepare(
            $this->baseSelect() . $whereSql . '
             ORDER BY c.updated_at DESC, c.id DESC
             LIMIT :limit OFFSET :offset'
        );
        foreach ($params as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }
        $statement->bindValue(':limit', $criteria->perPage, \PDO::PARAM_INT);
        $statement->bindValue(':offset', $criteria->offset(), \PDO::PARAM_INT);
        $statement->execute();

        $rows = $statement->fetchAll() ?: [];
        $items = array_map(static fn (array $row): Content => Content::fromArray($row), $rows);

        return [
            'items' => $items,
            'total' => $total,
            'page' => $criteria->page,
            'per_page' => $criteria->perPage,
            'last_page' => max(1, (int) ceil($total / $criteria->perPage)),
        ];
    }

    public function getParts(int $contentId): array
    {
        $statement = $this->prepare(
            'SELECT *
             FROM content_parts
             WHERE content_id = :content_id AND deleted_at IS NULL
             ORDER BY sort_order ASC, id ASC'
        );
        $statement->execute(['content_id' => $contentId]);
        $rows = $statement->fetchAll() ?: [];

        return array_map(static fn (array $row): ContentPart => ContentPart::fromArray($row), $rows);
    }

    public function getPublicParts(int $contentId): array
    {
        $statement = $this->prepare(
            'SELECT *
             FROM content_parts
             WHERE content_id = :content_id
               AND deleted_at IS NULL
               AND (release_at IS NULL OR release_at <= NOW())
             ORDER BY sort_order ASC, id ASC'
        );
        $statement->execute(['content_id' => $contentId]);
        $rows = $statement->fetchAll() ?: [];

        return array_map(static fn (array $row): ContentPart => ContentPart::fromArray($row), $rows);
    }

    private function assignContentCode(int $contentId): void
    {
        $statement = $this->prepare(
            'UPDATE contents
             SET content_code = :content_code, updated_at = NOW()
             WHERE id = :id'
        );
        $statement->execute([
            'id' => $contentId,
            'content_code' => sprintf('CNT-%06d', $contentId),
        ]);
    }

    private function temporaryContentCode(): string
    {
        return 'TMP-' . bin2hex(random_bytes(6));
    }

    private function nextSortOrder(int $contentId): int
    {
        $statement = $this->prepare(
            'SELECT COALESCE(MAX(sort_order), 0) + 1
             FROM content_parts
             WHERE content_id = :content_id'
        );
        $statement->execute(['content_id' => $contentId]);

        return (int) $statement->fetchColumn();
    }

    private function baseSelect(): string
    {
        return 'SELECT c.*, cp.display_name AS creator_display_name, cp.slug AS creator_slug, cp.creator_code
                FROM contents c
                INNER JOIN creator_profiles cp ON cp.id = c.creator_id';
    }
}
