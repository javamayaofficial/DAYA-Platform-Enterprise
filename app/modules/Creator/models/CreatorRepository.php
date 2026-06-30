<?php

declare(strict_types=1);

namespace App\Modules\Creator\Models;

use App\Core\Modular\BaseRepository;
use App\Modules\Creator\Dto\CreatorSearchCriteria;
use PDO;
use RuntimeException;

final class CreatorRepository extends BaseRepository
{
    public function create(
        array $profile,
        array $application,
        array $settings,
        array $categories,
        array $skills,
        int $userId
    ): int
    {
        $pdo = $this->pdo();
        $pdo->beginTransaction();

        try {
            $statement = $this->prepare(
                'INSERT INTO creator_profiles (
                    user_id, creator_code, slug, handle, display_name, creator_type, creator_level, creator_rank,
                    verification_status, tagline, bio, category, location, avatar_url, cover_image_url, website_url,
                    public_email, seo_title, seo_description, status, kyc_status, public_page_enabled, allow_public_contact, show_portfolio_publicly,
                    profile_view_count, approved_at, approved_by_user_id, rejected_at, rejected_by_user_id,
                    review_notes, deleted_at, created_at, updated_at
                ) VALUES (
                    :user_id, :creator_code, :slug, :handle, :display_name, :creator_type, :creator_level, :creator_rank,
                    :verification_status, :tagline, :bio, :category, :location, :avatar_url, :cover_image_url, :website_url,
                    :public_email, :seo_title, :seo_description, :status, :kyc_status, :public_page_enabled, :allow_public_contact, :show_portfolio_publicly,
                    0, NULL, NULL, NULL, NULL, NULL, NULL, NOW(), NOW()
                )'
            );
            $statement->execute([
                'user_id' => $userId,
                'creator_code' => $this->temporaryCreatorCode(),
                'slug' => $profile['slug'],
                'handle' => $profile['handle'],
                'display_name' => $profile['display_name'],
                'creator_type' => $profile['creator_type'],
                'creator_level' => 'emerging',
                'creator_rank' => 0,
                'verification_status' => 'pending_review',
                'tagline' => $profile['tagline'],
                'bio' => $profile['bio'],
                'category' => $profile['category'],
                'location' => $profile['location'],
                'avatar_url' => $profile['avatar_url'],
                'cover_image_url' => $profile['cover_image_url'],
                'website_url' => $profile['website_url'],
                'public_email' => $profile['public_email'],
                'seo_title' => $settings['seo_title'],
                'seo_description' => $settings['seo_description'],
                'status' => 'pending_review',
                'kyc_status' => 'pending_review',
                'public_page_enabled' => $settings['public_page_enabled'] ? 1 : 0,
                'allow_public_contact' => $settings['allow_public_contact'] ? 1 : 0,
                'show_portfolio_publicly' => $settings['show_portfolio_publicly'] ? 1 : 0,
            ]);

            $creatorId = $this->lastInsertId();
            $this->assignCreatorCode($creatorId);

            $applicationStatement = $this->prepare(
                'INSERT INTO creator_applications (
                    creator_id, status, application_note, kyc_full_name, kyc_document_type, kyc_document_number,
                    kyc_document_url, submitted_at, reviewed_at, reviewed_by_user_id, review_notes, created_at, updated_at
                ) VALUES (
                    :creator_id, :status, :application_note, :kyc_full_name, :kyc_document_type, :kyc_document_number,
                    :kyc_document_url, NOW(), NULL, NULL, NULL, NOW(), NOW()
                )'
            );
            $applicationStatement->execute([
                'creator_id' => $creatorId,
                'status' => 'pending_review',
                'application_note' => $application['application_note'],
                'kyc_full_name' => $application['kyc_full_name'],
                'kyc_document_type' => $application['kyc_document_type'],
                'kyc_document_number' => $application['kyc_document_number'],
                'kyc_document_url' => $application['kyc_document_url'],
            ]);

            $this->initializeStatistics($creatorId);
            $this->syncCategories($creatorId, $this->mergeCategories($profile['category'], $categories));
            $this->syncSkills($creatorId, $skills);

            $pdo->commit();

            return $creatorId;
        } catch (\Throwable $throwable) {
            $pdo->rollBack();

            throw new RuntimeException('Failed to create creator profile.', 0, $throwable);
        }
    }

    public function findById(int $creatorId, bool $includeDeleted = false): ?Creator
    {
        $sql = 'SELECT cp.*, u.name AS user_name, u.email AS user_email
                FROM creator_profiles cp
                INNER JOIN users u ON u.id = cp.user_id
                WHERE cp.id = :id';

        if (!$includeDeleted) {
            $sql .= ' AND cp.deleted_at IS NULL';
        }

        $sql .= ' LIMIT 1';
        $statement = $this->prepare($sql);
        $statement->execute(['id' => $creatorId]);
        $row = $statement->fetch();
        if (!is_array($row)) {
            return null;
        }

        $creator = Creator::fromArray($row);
        $creator->categories = $this->getCategories($creatorId);
        $creator->skills = $this->getSkills($creatorId);
        $creator->socialLinks = $this->getSocialLinks($creatorId);
        $creator->portfolioItems = $this->getPortfolioItems($creatorId);
        $creator->achievements = $this->getAchievements($creatorId);
        $creator->badges = $this->getBadges($creatorId);
        $creator->statistics = $this->getStatisticsSnapshot($creatorId);
        $creator->application = $this->getLatestApplication($creatorId);

        return $creator;
    }

    public function findByUserId(int $userId, bool $includeDeleted = false): ?Creator
    {
        $sql = 'SELECT cp.*, u.name AS user_name, u.email AS user_email
                FROM creator_profiles cp
                INNER JOIN users u ON u.id = cp.user_id
                WHERE cp.user_id = :user_id';

        if (!$includeDeleted) {
            $sql .= ' AND cp.deleted_at IS NULL';
        }

        $sql .= ' ORDER BY cp.id DESC LIMIT 1';
        $statement = $this->prepare($sql);
        $statement->execute(['user_id' => $userId]);
        $row = $statement->fetch();

        if (!is_array($row)) {
            return null;
        }

        return $this->findById((int) $row['id'], $includeDeleted);
    }

    public function findPublicByIdentifier(string $identifier): ?Creator
    {
        $statement = $this->prepare(
            'SELECT cp.*, u.name AS user_name, u.email AS user_email
             FROM creator_profiles cp
             INNER JOIN users u ON u.id = cp.user_id
             WHERE (cp.handle = :handle_identifier OR cp.slug = :slug_identifier)
               AND cp.status = :status
               AND cp.public_page_enabled = 1
               AND cp.deleted_at IS NULL
             LIMIT 1'
        );
        $statement->execute([
            'handle_identifier' => $identifier,
            'slug_identifier' => $identifier,
            'status' => 'active',
        ]);
        $row = $statement->fetch();

        if (!is_array($row)) {
            return null;
        }

        return $this->findById((int) $row['id']);
    }

    public function handleExists(string $handle, ?int $ignoreCreatorId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM creator_profiles WHERE handle = :handle';
        $params = ['handle' => $handle];

        if ($ignoreCreatorId !== null) {
            $sql .= ' AND id != :ignore_id';
            $params['ignore_id'] = $ignoreCreatorId;
        }

        $statement = $this->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }

    public function slugExists(string $slug, ?int $ignoreCreatorId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM creator_profiles WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($ignoreCreatorId !== null) {
            $sql .= ' AND id != :ignore_id';
            $params['ignore_id'] = $ignoreCreatorId;
        }

        $statement = $this->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }

    public function updateProfile(int $creatorId, array $profile, array $categories, array $skills): void
    {
        $pdo = $this->pdo();
        $pdo->beginTransaction();

        try {
            $statement = $this->prepare(
                'UPDATE creator_profiles
                 SET slug = :slug,
                     handle = :handle,
                     display_name = :display_name,
                     creator_type = :creator_type,
                     tagline = :tagline,
                     bio = :bio,
                     category = :category,
                     location = :location,
                     avatar_url = :avatar_url,
                     cover_image_url = :cover_image_url,
                     website_url = :website_url,
                     public_email = :public_email,
                     seo_title = :seo_title,
                     seo_description = :seo_description,
                     updated_at = NOW()
                 WHERE id = :id AND deleted_at IS NULL'
            );
            $statement->execute([
                'id' => $creatorId,
                'slug' => $profile['slug'],
                'handle' => $profile['handle'],
                'display_name' => $profile['display_name'],
                'creator_type' => $profile['creator_type'],
                'tagline' => $profile['tagline'],
                'bio' => $profile['bio'],
                'category' => $profile['category'],
                'location' => $profile['location'],
                'avatar_url' => $profile['avatar_url'],
                'cover_image_url' => $profile['cover_image_url'],
                'website_url' => $profile['website_url'],
                'public_email' => $profile['public_email'],
                'seo_title' => $profile['seo_title'],
                'seo_description' => $profile['seo_description'],
            ]);

            $this->syncCategories($creatorId, $this->mergeCategories($profile['category'], $categories));
            $this->syncSkills($creatorId, $skills);

            $pdo->commit();
        } catch (\Throwable $throwable) {
            $pdo->rollBack();

            throw new RuntimeException('Failed to update creator profile.', 0, $throwable);
        }
    }

    public function updateSettings(int $creatorId, array $settings): void
    {
        $statement = $this->prepare(
            'UPDATE creator_profiles
             SET public_page_enabled = :public_page_enabled,
                 allow_public_contact = :allow_public_contact,
                 show_portfolio_publicly = :show_portfolio_publicly,
                 seo_title = :seo_title,
                 seo_description = :seo_description,
                 updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $creatorId,
            'public_page_enabled' => $settings['public_page_enabled'] ? 1 : 0,
            'allow_public_contact' => $settings['allow_public_contact'] ? 1 : 0,
            'show_portfolio_publicly' => $settings['show_portfolio_publicly'] ? 1 : 0,
            'seo_title' => $settings['seo_title'],
            'seo_description' => $settings['seo_description'],
        ]);
    }

    public function addSocialLink(int $creatorId, array $data): void
    {
        $statement = $this->prepare(
            'INSERT INTO creator_social_links (creator_id, platform, url, sort_order, deleted_at, created_at, updated_at)
             VALUES (:creator_id, :platform, :url, :sort_order, NULL, NOW(), NOW())'
        );
        $statement->execute([
            'creator_id' => $creatorId,
            'platform' => $data['platform'],
            'url' => $data['url'],
            'sort_order' => $this->nextSortOrder('creator_social_links', $creatorId),
        ]);
    }

    public function deleteSocialLink(int $creatorId, int $linkId): void
    {
        $statement = $this->prepare(
            'UPDATE creator_social_links
             SET deleted_at = NOW(), updated_at = NOW()
             WHERE id = :id AND creator_id = :creator_id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $linkId,
            'creator_id' => $creatorId,
        ]);
    }

    public function addPortfolioItem(int $creatorId, array $data): void
    {
        if (!empty($data['is_featured'])) {
            $this->clearFeaturedPortfolio($creatorId);
        }

        $statement = $this->prepare(
            'INSERT INTO creator_portfolios (
                creator_id, portfolio_type, title, summary, organization, issued_at, ended_at, url, thumbnail_url, is_featured, sort_order, deleted_at, created_at, updated_at
             ) VALUES (
                :creator_id, :portfolio_type, :title, :summary, :organization, :issued_at, :ended_at, :url, :thumbnail_url, :is_featured, :sort_order, NULL, NOW(), NOW()
             )'
        );
        $statement->execute([
            'creator_id' => $creatorId,
            'portfolio_type' => $data['portfolio_type'],
            'title' => $data['title'],
            'summary' => $data['summary'],
            'organization' => $data['organization'],
            'issued_at' => $data['issued_at'] !== '' ? $data['issued_at'] : null,
            'ended_at' => $data['ended_at'] !== '' ? $data['ended_at'] : null,
            'url' => $data['url'],
            'thumbnail_url' => $data['thumbnail_url'],
            'is_featured' => !empty($data['is_featured']) ? 1 : 0,
            'sort_order' => $this->nextSortOrder('creator_portfolios', $creatorId),
        ]);
    }

    public function deletePortfolioItem(int $creatorId, int $itemId): void
    {
        $statement = $this->prepare(
            'UPDATE creator_portfolios
             SET deleted_at = NOW(), updated_at = NOW()
             WHERE id = :id AND creator_id = :creator_id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $itemId,
            'creator_id' => $creatorId,
        ]);
    }

    public function addAchievement(int $creatorId, array $data): void
    {
        $statement = $this->prepare(
            'INSERT INTO creator_achievements (
                creator_id, title, issuer, description, achieved_at, url, sort_order, deleted_at, created_at, updated_at
             ) VALUES (
                :creator_id, :title, :issuer, :description, :achieved_at, :url, :sort_order, NULL, NOW(), NOW()
             )'
        );
        $statement->execute([
            'creator_id' => $creatorId,
            'title' => $data['title'],
            'issuer' => $data['issuer'],
            'description' => $data['description'],
            'achieved_at' => $data['achieved_at'] !== '' ? $data['achieved_at'] : date('Y-m-d'),
            'url' => $data['url'],
            'sort_order' => $this->nextSortOrder('creator_achievements', $creatorId),
        ]);
    }

    public function deleteAchievement(int $creatorId, int $achievementId): void
    {
        $statement = $this->prepare(
            'UPDATE creator_achievements
             SET deleted_at = NOW(), updated_at = NOW()
             WHERE id = :id AND creator_id = :creator_id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $achievementId,
            'creator_id' => $creatorId,
        ]);
    }

    public function review(int $creatorId, array $review): void
    {
        $isApproved = $review['status'] === 'active';
        $pdo = $this->pdo();
        $pdo->beginTransaction();

        try {
            $statement = $this->prepare(
                'UPDATE creator_profiles
                 SET status = :status,
                     kyc_status = :kyc_status,
                     verification_status = :verification_status,
                     creator_level = :creator_level,
                     creator_rank = :creator_rank,
                     approved_at = :approved_at,
                     approved_by_user_id = :approved_by_user_id,
                     rejected_at = :rejected_at,
                     rejected_by_user_id = :rejected_by_user_id,
                     review_notes = :review_notes,
                     updated_at = NOW()
                 WHERE id = :id AND deleted_at IS NULL'
            );
            $statement->execute([
                'id' => $creatorId,
                'status' => $review['status'],
                'kyc_status' => $isApproved ? 'verified' : ($review['status'] === 'rejected' ? 'rejected' : 'pending_review'),
                'verification_status' => $review['verification_status'],
                'creator_level' => $review['creator_level'],
                'creator_rank' => (int) $review['creator_rank'],
                'approved_at' => $isApproved ? date('Y-m-d H:i:s') : null,
                'approved_by_user_id' => $isApproved ? (int) $review['reviewed_by_user_id'] : null,
                'rejected_at' => !$isApproved && $review['status'] === 'rejected' ? date('Y-m-d H:i:s') : null,
                'rejected_by_user_id' => !$isApproved && $review['status'] === 'rejected' ? (int) $review['reviewed_by_user_id'] : null,
                'review_notes' => $review['review_notes'],
            ]);

            $applicationStatement = $this->prepare(
                'UPDATE creator_applications
                 SET status = :status,
                     reviewed_at = NOW(),
                     reviewed_by_user_id = :reviewed_by_user_id,
                     review_notes = :review_notes,
                     updated_at = NOW()
                 WHERE creator_id = :creator_id
                 ORDER BY id DESC
                 LIMIT 1'
            );
            $applicationStatement->execute([
                'creator_id' => $creatorId,
                'status' => $review['status'],
                'reviewed_by_user_id' => (int) $review['reviewed_by_user_id'],
                'review_notes' => $review['review_notes'],
            ]);

            $this->syncBadges($creatorId, (array) $review['badges']);
            $this->updateStatisticsSnapshot($creatorId, (array) $review['statistics']);
            $pdo->commit();
        } catch (\Throwable $throwable) {
            $pdo->rollBack();

            throw new RuntimeException('Failed to review creator profile.', 0, $throwable);
        }
    }

    public function incrementProfileViewCount(int $creatorId): void
    {
        $statement = $this->prepare(
            'UPDATE creator_profiles
             SET profile_view_count = profile_view_count + 1, updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute(['id' => $creatorId]);
    }

    public function softDelete(int $creatorId): void
    {
        $statement = $this->prepare(
            'UPDATE creator_profiles
             SET status = :status,
                 verification_status = :verification_status,
                 public_page_enabled = 0,
                 deleted_at = NOW(),
                 updated_at = NOW()
             WHERE id = :id AND deleted_at IS NULL'
        );
        $statement->execute([
            'id' => $creatorId,
            'status' => 'revoked',
            'verification_status' => 'rejected',
        ]);
    }

    public function paginate(CreatorSearchCriteria $criteria): array
    {
        $params = [];
        $conditions = [];

        if (!$criteria->includeDeleted) {
            $conditions[] = 'cp.deleted_at IS NULL';
        }

        if ($criteria->publicOnly) {
            $conditions[] = 'cp.status = :public_status';
            $conditions[] = 'cp.public_page_enabled = 1';
            $params['public_status'] = 'active';
        }

        if ($criteria->search !== '') {
            $conditions[] = '(cp.creator_code LIKE :search OR cp.slug LIKE :search OR cp.handle LIKE :search OR cp.display_name LIKE :search OR u.name LIKE :search OR cp.category LIKE :search OR cp.creator_type LIKE :search)';
            $params['search'] = '%' . $criteria->search . '%';
        }

        if ($criteria->status !== '') {
            $conditions[] = 'cp.status = :status';
            $params['status'] = $criteria->status;
        }

        if ($criteria->category !== '') {
            $conditions[] = 'cp.category = :category';
            $params['category'] = $criteria->category;
        }

        $where = $conditions === [] ? '' : ' WHERE ' . implode(' AND ', $conditions);
        $countStatement = $this->prepare(
            'SELECT COUNT(*)
             FROM creator_profiles cp
             INNER JOIN users u ON u.id = cp.user_id' . $where
        );
        $countStatement->execute($params);
        $total = (int) $countStatement->fetchColumn();

        $statement = $this->prepare(
            'SELECT cp.*, u.name AS user_name, u.email AS user_email
             FROM creator_profiles cp
             INNER JOIN users u ON u.id = cp.user_id' . $where . '
             ORDER BY cp.updated_at DESC
             LIMIT :limit OFFSET :offset'
        );
        foreach ($params as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }
        $statement->bindValue(':limit', $criteria->perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $criteria->offset(), PDO::PARAM_INT);
        $statement->execute();
        $rows = $statement->fetchAll() ?: [];

        return [
            'items' => array_map(static fn (array $row): Creator => Creator::fromArray($row), $rows),
            'total' => $total,
        ];
    }

    public function assignCreatorRole(int $userId): void
    {
        $statement = $this->prepare(
            'INSERT INTO user_roles (user_id, role_id, created_at)
             SELECT :user_id, r.id, NOW()
             FROM roles r
             WHERE r.slug = :role_slug
             AND NOT EXISTS (
                 SELECT 1
                 FROM user_roles ur
                 WHERE ur.user_id = :user_id_check AND ur.role_id = r.id
             )'
        );
        $statement->execute([
            'user_id' => $userId,
            'role_slug' => 'creator',
            'user_id_check' => $userId,
        ]);
    }

    public function getStatistics(Creator $creator): array
    {
        $statistics = $this->getStatisticsSnapshot($creator->id);

        return [
            'profile_views' => $creator->profileViewCount,
            'followers_count' => (int) ($statistics['followers_count'] ?? 0),
            'reads_count' => (int) ($statistics['reads_count'] ?? 0),
            'listens_count' => (int) ($statistics['listens_count'] ?? 0),
            'downloads_count' => (int) ($statistics['downloads_count'] ?? 0),
            'sponsor_count' => (int) ($statistics['sponsor_count'] ?? 0),
            'donation_count' => (int) ($statistics['donation_count'] ?? 0),
            'affiliate_count' => (int) ($statistics['affiliate_count'] ?? 0),
            'revenue_minor' => (int) ($statistics['revenue_minor'] ?? 0),
            'wallet_available_minor' => (int) ($statistics['wallet_available_minor'] ?? 0),
            'wallet_pending_minor' => (int) ($statistics['wallet_pending_minor'] ?? 0),
            'skills' => count($this->getSkills($creator->id)),
            'achievements' => count($this->getAchievements($creator->id)),
            'badges' => count($this->getBadges($creator->id)),
            'social_links' => count($this->getSocialLinks($creator->id)),
            'portfolio_items' => count($this->getPortfolioItems($creator->id)),
            'featured_portfolio_items' => count(array_filter(
                $this->getPortfolioItems($creator->id),
                static fn (CreatorPortfolioItem $item): bool => $item->isFeatured
            )),
        ];
    }

    public function getSocialLinks(int $creatorId): array
    {
        $statement = $this->prepare(
            'SELECT *
             FROM creator_social_links
             WHERE creator_id = :creator_id AND deleted_at IS NULL
             ORDER BY sort_order ASC, id ASC'
        );
        $statement->execute(['creator_id' => $creatorId]);
        $rows = $statement->fetchAll() ?: [];

        return array_map(static fn (array $row): CreatorSocialLink => CreatorSocialLink::fromArray($row), $rows);
    }

    public function getPortfolioItems(int $creatorId): array
    {
        $statement = $this->prepare(
            'SELECT *
             FROM creator_portfolios
             WHERE creator_id = :creator_id AND deleted_at IS NULL
             ORDER BY is_featured DESC, sort_order ASC, id ASC'
        );
        $statement->execute(['creator_id' => $creatorId]);
        $rows = $statement->fetchAll() ?: [];

        return array_map(static fn (array $row): CreatorPortfolioItem => CreatorPortfolioItem::fromArray($row), $rows);
    }

    public function getCategories(int $creatorId): array
    {
        $statement = $this->prepare(
            'SELECT *
             FROM creator_categories
             WHERE creator_id = :creator_id
             ORDER BY sort_order ASC, id ASC'
        );
        $statement->execute(['creator_id' => $creatorId]);
        $rows = $statement->fetchAll() ?: [];

        return array_map(static fn (array $row): CreatorCategory => CreatorCategory::fromArray($row), $rows);
    }

    public function getSkills(int $creatorId): array
    {
        $statement = $this->prepare(
            'SELECT *
             FROM creator_skills
             WHERE creator_id = :creator_id
             ORDER BY sort_order ASC, id ASC'
        );
        $statement->execute(['creator_id' => $creatorId]);
        $rows = $statement->fetchAll() ?: [];

        return array_map(static fn (array $row): CreatorSkill => CreatorSkill::fromArray($row), $rows);
    }

    public function getAchievements(int $creatorId): array
    {
        $statement = $this->prepare(
            'SELECT *
             FROM creator_achievements
             WHERE creator_id = :creator_id AND deleted_at IS NULL
             ORDER BY achieved_at DESC, sort_order ASC, id ASC'
        );
        $statement->execute(['creator_id' => $creatorId]);
        $rows = $statement->fetchAll() ?: [];

        return array_map(static fn (array $row): CreatorAchievement => CreatorAchievement::fromArray($row), $rows);
    }

    public function getBadges(int $creatorId): array
    {
        $statement = $this->prepare(
            'SELECT *
             FROM creator_badges
             WHERE creator_id = :creator_id
             ORDER BY assigned_at DESC, id ASC'
        );
        $statement->execute(['creator_id' => $creatorId]);
        $rows = $statement->fetchAll() ?: [];

        return array_map(static fn (array $row): CreatorBadge => CreatorBadge::fromArray($row), $rows);
    }

    public function getStatisticsSnapshot(int $creatorId): ?array
    {
        $statement = $this->prepare(
            'SELECT *
             FROM creator_statistics
             WHERE creator_id = :creator_id
             LIMIT 1'
        );
        $statement->execute(['creator_id' => $creatorId]);
        $row = $statement->fetch();

        if (!is_array($row)) {
            return null;
        }

        return CreatorStatistics::fromArray($row)->toArray();
    }

    public function getLatestApplication(int $creatorId): ?array
    {
        $statement = $this->prepare(
            'SELECT *
             FROM creator_applications
             WHERE creator_id = :creator_id
             ORDER BY id DESC
             LIMIT 1'
        );
        $statement->execute(['creator_id' => $creatorId]);
        $row = $statement->fetch();

        if (!is_array($row)) {
            return null;
        }

        return CreatorApplication::fromArray($row)->toArray();
    }

    private function initializeStatistics(int $creatorId): void
    {
        $statement = $this->prepare(
            'INSERT INTO creator_statistics (
                creator_id, followers_count, reads_count, listens_count, downloads_count, sponsor_count,
                donation_count, affiliate_count, revenue_minor, wallet_available_minor, wallet_pending_minor,
                created_at, updated_at
             ) VALUES (
                :creator_id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NOW(), NOW()
             )'
        );
        $statement->execute(['creator_id' => $creatorId]);
    }

    private function updateStatisticsSnapshot(int $creatorId, array $statistics): void
    {
        $statement = $this->prepare(
            'UPDATE creator_statistics
             SET followers_count = :followers_count,
                 reads_count = :reads_count,
                 listens_count = :listens_count,
                 downloads_count = :downloads_count,
                 sponsor_count = :sponsor_count,
                 donation_count = :donation_count,
                 affiliate_count = :affiliate_count,
                 revenue_minor = :revenue_minor,
                 wallet_available_minor = :wallet_available_minor,
                 wallet_pending_minor = :wallet_pending_minor,
                 updated_at = NOW()
             WHERE creator_id = :creator_id'
        );
        $statement->execute([
            'creator_id' => $creatorId,
            'followers_count' => (int) ($statistics['followers_count'] ?? 0),
            'reads_count' => (int) ($statistics['reads_count'] ?? 0),
            'listens_count' => (int) ($statistics['listens_count'] ?? 0),
            'downloads_count' => (int) ($statistics['downloads_count'] ?? 0),
            'sponsor_count' => (int) ($statistics['sponsor_count'] ?? 0),
            'donation_count' => (int) ($statistics['donation_count'] ?? 0),
            'affiliate_count' => (int) ($statistics['affiliate_count'] ?? 0),
            'revenue_minor' => (int) ($statistics['revenue_minor'] ?? 0),
            'wallet_available_minor' => (int) ($statistics['wallet_available_minor'] ?? 0),
            'wallet_pending_minor' => (int) ($statistics['wallet_pending_minor'] ?? 0),
        ]);
    }

    private function syncCategories(int $creatorId, array $categories): void
    {
        $this->prepare('DELETE FROM creator_categories WHERE creator_id = :creator_id')
            ->execute(['creator_id' => $creatorId]);

        if ($categories === []) {
            return;
        }

        $statement = $this->prepare(
            'INSERT INTO creator_categories (creator_id, name, sort_order, created_at)
             VALUES (:creator_id, :name, :sort_order, NOW())'
        );

        foreach (array_values($categories) as $index => $category) {
            $statement->execute([
                'creator_id' => $creatorId,
                'name' => $category,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function syncSkills(int $creatorId, array $skills): void
    {
        $this->prepare('DELETE FROM creator_skills WHERE creator_id = :creator_id')
            ->execute(['creator_id' => $creatorId]);

        if ($skills === []) {
            return;
        }

        $statement = $this->prepare(
            'INSERT INTO creator_skills (creator_id, name, sort_order, created_at)
             VALUES (:creator_id, :name, :sort_order, NOW())'
        );

        foreach (array_values($skills) as $index => $skill) {
            $statement->execute([
                'creator_id' => $creatorId,
                'name' => $skill,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function syncBadges(int $creatorId, array $badges): void
    {
        $this->prepare('DELETE FROM creator_badges WHERE creator_id = :creator_id')
            ->execute(['creator_id' => $creatorId]);

        if ($badges === []) {
            return;
        }

        $statement = $this->prepare(
            'INSERT INTO creator_badges (creator_id, badge_key, badge_label, assigned_at, created_at)
             VALUES (:creator_id, :badge_key, :badge_label, NOW(), NOW())'
        );

        foreach ($badges as $badgeKey => $badgeLabel) {
            $resolvedKey = is_string($badgeKey) ? $badgeKey : (string) $badgeLabel;
            $resolvedLabel = is_string($badgeLabel) ? $badgeLabel : (string) $badgeKey;

            $statement->execute([
                'creator_id' => $creatorId,
                'badge_key' => $resolvedKey,
                'badge_label' => $resolvedLabel,
            ]);
        }
    }

    private function assignCreatorCode(int $creatorId): void
    {
        $statement = $this->prepare(
            'UPDATE creator_profiles
             SET creator_code = :creator_code, updated_at = NOW()
             WHERE id = :id'
        );
        $statement->execute([
            'id' => $creatorId,
            'creator_code' => sprintf('CR-%06d', $creatorId),
        ]);
    }

    private function temporaryCreatorCode(): string
    {
        return 'TMP-' . bin2hex(random_bytes(6));
    }

    private function mergeCategories(string $primaryCategory, array $categories): array
    {
        $merged = array_merge([$primaryCategory], $categories);

        return array_values(array_unique(array_filter(
            array_map(static fn (mixed $item): string => trim((string) $item), $merged),
            static fn (string $item): bool => $item !== ''
        )));
    }

    private function nextSortOrder(string $tableName, int $creatorId): int
    {
        $statement = $this->prepare(
            sprintf('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM %s WHERE creator_id = :creator_id AND deleted_at IS NULL', $tableName)
        );
        $statement->execute(['creator_id' => $creatorId]);

        return (int) $statement->fetchColumn();
    }

    private function clearFeaturedPortfolio(int $creatorId): void
    {
        $statement = $this->prepare(
            'UPDATE creator_portfolios
             SET is_featured = 0, updated_at = NOW()
             WHERE creator_id = :creator_id AND deleted_at IS NULL'
        );
        $statement->execute(['creator_id' => $creatorId]);
    }
}
