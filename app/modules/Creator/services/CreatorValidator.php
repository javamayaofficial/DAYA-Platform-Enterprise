<?php

declare(strict_types=1);

namespace App\Modules\Creator\Services;

final class CreatorValidator
{
    public function __construct(
        private readonly array $categories,
        private readonly array $socialPlatforms,
        private readonly array $creatorTypes,
        private readonly array $creatorLevels,
        private readonly array $verificationStatuses,
        private readonly array $portfolioTypes,
        private readonly array $badgeCatalog
    ) {
    }

    public function validateRegistration(array $profile, array $application): array
    {
        $errors = $this->validateProfile($profile);

        if (trim((string) ($application['kyc_full_name'] ?? '')) === '') {
            $errors['kyc_full_name'] = 'Legal name is required.';
        }

        if (trim((string) ($application['kyc_document_type'] ?? '')) === '') {
            $errors['kyc_document_type'] = 'Document type is required.';
        }

        if (trim((string) ($application['kyc_document_number'] ?? '')) === '') {
            $errors['kyc_document_number'] = 'Document number is required.';
        }

        if (trim((string) ($application['application_note'] ?? '')) === '') {
            $errors['application_note'] = 'Application note is required.';
        }

        return ['errors' => $errors];
    }

    public function validateProfile(array $profile): array
    {
        $errors = [];

        $handle = (string) ($profile['handle'] ?? '');
        if ($handle === '' || !preg_match('/^[a-z0-9][a-z0-9\-]{2,29}$/', $handle)) {
            $errors['handle'] = 'Handle must be 3-30 chars using lowercase letters, numbers, or hyphen.';
        }

        if (trim((string) ($profile['display_name'] ?? '')) === '') {
            $errors['display_name'] = 'Display name is required.';
        }

        $slug = trim((string) ($profile['slug'] ?? ''));
        if ($slug === '' || !preg_match('/^[a-z0-9][a-z0-9\-]{2,79}$/', $slug)) {
            $errors['slug'] = 'Slug must be 3-80 chars using lowercase letters, numbers, or hyphen.';
        }

        if (!in_array((string) ($profile['creator_type'] ?? ''), $this->creatorTypes, true)) {
            $errors['creator_type'] = 'Creator type is not valid.';
        }

        $category = (string) ($profile['category'] ?? '');
        if ($category === '' || !in_array($category, $this->categories, true)) {
            $errors['category'] = 'Category is not valid.';
        }

        $avatarUrl = trim((string) ($profile['avatar_url'] ?? ''));
        if ($avatarUrl !== '' && filter_var($avatarUrl, FILTER_VALIDATE_URL) === false) {
            $errors['avatar_url'] = 'Avatar URL is not valid.';
        }

        $coverImageUrl = trim((string) ($profile['cover_image_url'] ?? ''));
        if ($coverImageUrl !== '' && filter_var($coverImageUrl, FILTER_VALIDATE_URL) === false) {
            $errors['cover_image_url'] = 'Cover image URL is not valid.';
        }

        $websiteUrl = trim((string) ($profile['website_url'] ?? ''));
        if ($websiteUrl !== '' && filter_var($websiteUrl, FILTER_VALIDATE_URL) === false) {
            $errors['website_url'] = 'Website URL is not valid.';
        }

        $publicEmail = trim((string) ($profile['public_email'] ?? ''));
        if ($publicEmail !== '' && filter_var($publicEmail, FILTER_VALIDATE_EMAIL) === false) {
            $errors['public_email'] = 'Public email is not valid.';
        }

        if (mb_strlen((string) ($profile['seo_title'] ?? '')) > 150) {
            $errors['seo_title'] = 'SEO title must not exceed 150 characters.';
        }

        if (mb_strlen((string) ($profile['seo_description'] ?? '')) > 255) {
            $errors['seo_description'] = 'SEO description must not exceed 255 characters.';
        }

        return $errors;
    }

    public function validateCollections(array $categories, array $skills): array
    {
        $errors = [];

        foreach ($categories as $index => $category) {
            if (!in_array($category, $this->categories, true)) {
                $errors['categories_' . $index] = 'One or more categories are not valid.';
                break;
            }
        }

        foreach ($skills as $index => $skill) {
            if ($skill === '' || mb_strlen($skill) > 80) {
                $errors['skills_' . $index] = 'Each skill must be 1-80 characters.';
                break;
            }
        }

        return ['errors' => $errors];
    }

    public function validateSocialLink(array $socialLink): array
    {
        $errors = [];
        $platform = (string) ($socialLink['platform'] ?? '');
        $url = trim((string) ($socialLink['url'] ?? ''));

        if ($platform === '' || !in_array($platform, $this->socialPlatforms, true)) {
            $errors['platform'] = 'Social platform is not valid.';
        }

        if ($url === '' || filter_var($url, FILTER_VALIDATE_URL) === false) {
            $errors['url'] = 'Social URL is not valid.';
        }

        return ['errors' => $errors];
    }

    public function validatePortfolio(array $portfolio): array
    {
        $errors = [];

        if (!in_array((string) ($portfolio['portfolio_type'] ?? ''), $this->portfolioTypes, true)) {
            $errors['portfolio_type'] = 'Portfolio type is not valid.';
        }

        if (trim((string) ($portfolio['title'] ?? '')) === '') {
            $errors['title'] = 'Portfolio title is required.';
        }

        $url = trim((string) ($portfolio['url'] ?? ''));
        if ($url === '' || filter_var($url, FILTER_VALIDATE_URL) === false) {
            $errors['url'] = 'Portfolio URL is not valid.';
        }

        $thumbnailUrl = trim((string) ($portfolio['thumbnail_url'] ?? ''));
        if ($thumbnailUrl !== '' && filter_var($thumbnailUrl, FILTER_VALIDATE_URL) === false) {
            $errors['thumbnail_url'] = 'Thumbnail URL is not valid.';
        }

        if (mb_strlen((string) ($portfolio['organization'] ?? '')) > 150) {
            $errors['organization'] = 'Organization must not exceed 150 characters.';
        }

        return ['errors' => $errors];
    }

    public function validateSettings(array $settings): array
    {
        $errors = [];

        if (mb_strlen((string) ($settings['seo_title'] ?? '')) > 150) {
            $errors['seo_title'] = 'SEO title must not exceed 150 characters.';
        }

        if (mb_strlen((string) ($settings['seo_description'] ?? '')) > 255) {
            $errors['seo_description'] = 'SEO description must not exceed 255 characters.';
        }

        return ['errors' => $errors];
    }

    public function validateAchievement(array $achievement): array
    {
        $errors = [];

        if (trim((string) ($achievement['title'] ?? '')) === '') {
            $errors['title'] = 'Achievement title is required.';
        }

        $url = trim((string) ($achievement['url'] ?? ''));
        if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL) === false) {
            $errors['url'] = 'Achievement URL is not valid.';
        }

        return ['errors' => $errors];
    }

    public function validateStatistics(array $statistics): array
    {
        $errors = [];

        foreach ($statistics as $key => $value) {
            if (!is_int($value) && filter_var($value, FILTER_VALIDATE_INT) === false) {
                $errors[$key] = 'Statistic value must be an integer.';
                break;
            }

            if ((int) $value < 0) {
                $errors[$key] = 'Statistic value must not be negative.';
                break;
            }
        }

        return ['errors' => $errors];
    }

    public function validateReview(array $review): array
    {
        $errors = [];

        if (!in_array((string) ($review['status'] ?? ''), ['active', 'rejected', 'suspended', 'revoked'], true)) {
            $errors['status'] = 'Review status is not valid.';
        }

        if (!in_array((string) ($review['verification_status'] ?? ''), $this->verificationStatuses, true)) {
            $errors['verification_status'] = 'Verification status is not valid.';
        }

        if (!in_array((string) ($review['creator_level'] ?? ''), $this->creatorLevels, true)) {
            $errors['creator_level'] = 'Creator level is not valid.';
        }

        if ((int) ($review['creator_rank'] ?? 0) < 0) {
            $errors['creator_rank'] = 'Creator rank must not be negative.';
        }

        foreach ((array) ($review['badges'] ?? []) as $badgeKey) {
            if (!in_array((string) $badgeKey, array_keys($this->badgeCatalog), true)) {
                $errors['badges'] = 'One or more badges are not valid.';
                break;
            }
        }

        $statisticValidation = $this->validateStatistics((array) ($review['statistics'] ?? []));
        if ($statisticValidation['errors'] !== []) {
            $errors = array_merge($errors, $statisticValidation['errors']);
        }

        return ['errors' => $errors];
    }
}
