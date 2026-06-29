<?php

declare(strict_types=1);

namespace App\Modules\Creator\Services;

use App\Core\Modular\BaseModule;
use App\Core\Modular\BaseService;
use App\Modules\Authentication\Models\RoleRepository;
use App\Modules\Creator\Dto\CreatorSearchCriteria;
use App\Modules\Creator\Models\Creator;
use App\Modules\Creator\Models\CreatorRepository;
use App\Modules\Creator\Resources\CreatorResource;
use RuntimeException;

final class CreatorService extends BaseService
{
    public function __construct(
        BaseModule $module,
        private readonly CreatorRepository $repository,
        private readonly RoleRepository $roleRepository
    ) {
        parent::__construct($module);
    }

    public function register(int $userId, array $profile, array $application, array $settings): Creator
    {
        $profile = $this->prepareProfile($profile, $settings);
        $existing = $this->repository->findByUserId($userId, true);
        if ($existing instanceof Creator) {
            throw new RuntimeException('User already has a creator profile.');
        }

        if ($this->repository->handleExists((string) $profile['handle'])) {
            throw new RuntimeException('Creator handle is already in use.');
        }

        if ($this->repository->slugExists((string) $profile['slug'])) {
            throw new RuntimeException('Creator slug is already in use.');
        }

        $creatorId = $this->repository->create(
            $profile,
            $application,
            $settings,
            (array) ($profile['categories'] ?? []),
            (array) ($profile['skills'] ?? []),
            $userId
        );
        $this->repository->assignCreatorRole($userId);

        return $this->requireCreator($creatorId, true);
    }

    public function updateProfile(int $creatorId, array $profile): Creator
    {
        $profile = $this->prepareProfile($profile, [
            'seo_title' => $profile['seo_title'] ?? '',
            'seo_description' => $profile['seo_description'] ?? '',
        ]);

        if ($this->repository->handleExists((string) $profile['handle'], $creatorId)) {
            throw new RuntimeException('Creator handle is already in use.');
        }

        if ($this->repository->slugExists((string) $profile['slug'], $creatorId)) {
            throw new RuntimeException('Creator slug is already in use.');
        }

        $this->repository->updateProfile(
            $creatorId,
            $profile,
            (array) ($profile['categories'] ?? []),
            (array) ($profile['skills'] ?? [])
        );

        return $this->requireCreator($creatorId, true);
    }

    public function updateSettings(int $creatorId, array $settings): Creator
    {
        $this->repository->updateSettings($creatorId, $settings);

        return $this->requireCreator($creatorId, true);
    }

    public function addSocialLink(int $creatorId, array $socialLink): Creator
    {
        $this->repository->addSocialLink($creatorId, $socialLink);

        return $this->requireCreator($creatorId, true);
    }

    public function deleteSocialLink(int $creatorId, int $linkId): Creator
    {
        $this->repository->deleteSocialLink($creatorId, $linkId);

        return $this->requireCreator($creatorId, true);
    }

    public function addPortfolioItem(int $creatorId, array $portfolio): Creator
    {
        $this->repository->addPortfolioItem($creatorId, $portfolio);

        return $this->requireCreator($creatorId, true);
    }

    public function addAchievement(int $creatorId, array $achievement): Creator
    {
        $this->repository->addAchievement($creatorId, $achievement);

        return $this->requireCreator($creatorId, true);
    }

    public function deleteAchievement(int $creatorId, int $achievementId): Creator
    {
        $this->repository->deleteAchievement($creatorId, $achievementId);

        return $this->requireCreator($creatorId, true);
    }

    public function deletePortfolioItem(int $creatorId, int $itemId): Creator
    {
        $this->repository->deletePortfolioItem($creatorId, $itemId);

        return $this->requireCreator($creatorId, true);
    }

    public function review(int $creatorId, array $review): Creator
    {
        $this->repository->review($creatorId, $review);

        return $this->requireCreator($creatorId, true);
    }

    public function delete(int $creatorId): void
    {
        $this->repository->softDelete($creatorId);
    }

    public function detailById(int $creatorId, bool $includeDeleted = false): Creator
    {
        return $this->requireCreator($creatorId, $includeDeleted);
    }

    public function detailByUserId(int $userId, bool $includeDeleted = false): ?Creator
    {
        return $this->repository->findByUserId($userId, $includeDeleted);
    }

    public function detailByHandle(string $handle): Creator
    {
        $creator = $this->repository->findPublicByIdentifier($handle);
        if (!$creator instanceof Creator) {
            throw new RuntimeException('Creator not found.');
        }

        $this->repository->incrementProfileViewCount($creator->id);

        return $this->requireCreator($creator->id, false);
    }

    public function paginate(CreatorSearchCriteria $criteria): array
    {
        $result = $this->repository->paginate($criteria);
        $items = array_map(static fn (Creator $creator): array => CreatorResource::listItem($creator), $result['items']);

        return [
            'items' => $items,
            'total' => (int) $result['total'],
            'page' => $criteria->page,
            'per_page' => $criteria->perPage,
            'last_page' => max(1, (int) ceil(((int) $result['total']) / $criteria->perPage)),
        ];
    }

    public function detailResource(Creator $creator): array
    {
        return CreatorResource::detail($creator);
    }

    public function statistics(Creator $creator): array
    {
        return $this->repository->getStatistics($creator);
    }

    public function rolesForUser(int $userId): array
    {
        return $this->roleRepository->getRoleSlugsForUser($userId);
    }

    private function requireCreator(int $creatorId, bool $includeDeleted): Creator
    {
        $creator = $this->repository->findById($creatorId, $includeDeleted);
        if (!$creator instanceof Creator) {
            throw new RuntimeException('Creator profile not found.');
        }

        return $creator;
    }

    private function prepareProfile(array $profile, array $settings): array
    {
        $slug = trim((string) ($profile['slug'] ?? ''));
        if ($slug === '') {
            $slug = trim((string) ($profile['handle'] ?? ''));
        }

        $profile['slug'] = strtolower($slug);
        $profile['seo_title'] = trim((string) ($profile['seo_title'] ?? $settings['seo_title'] ?? ''));
        $profile['seo_description'] = trim((string) ($profile['seo_description'] ?? $settings['seo_description'] ?? ''));

        if ($profile['seo_title'] === '') {
            $profile['seo_title'] = (string) ($profile['display_name'] ?? '');
        }

        if ($profile['seo_description'] === '') {
            $profile['seo_description'] = (string) ($profile['tagline'] ?? '');
        }

        return $profile;
    }
}
