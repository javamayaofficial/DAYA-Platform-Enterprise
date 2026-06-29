<?php

declare(strict_types=1);

namespace App\Modules\Content\Services;

final class ContentValidator
{
    public function __construct(
        private readonly array $contentTypes,
        private readonly array $statuses,
        private readonly array $reviewStatuses,
        private readonly array $accessPolicies,
        private readonly array $visibilityOptions
    ) {
    }

    public function validateContent(array $content): array
    {
        $errors = [];

        if (!in_array((string) ($content['content_type'] ?? ''), $this->contentTypes, true)) {
            $errors['content_type'] = 'Content type is not valid.';
        }

        if (trim((string) ($content['title'] ?? '')) === '') {
            $errors['title'] = 'Title is required.';
        }

        if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,99}$/', (string) ($content['slug'] ?? ''))) {
            $errors['slug'] = 'Slug must be 3-100 chars using lowercase letters, numbers, or hyphen.';
        }

        if (trim((string) ($content['body'] ?? '')) === '') {
            $errors['body'] = 'Body is required.';
        }

        if (!in_array((string) ($content['access_policy'] ?? ''), $this->accessPolicies, true)) {
            $errors['access_policy'] = 'Access policy is not valid.';
        }

        if (!in_array((string) ($content['visibility'] ?? ''), $this->visibilityOptions, true)) {
            $errors['visibility'] = 'Visibility is not valid.';
        }

        if ((int) ($content['price_minor'] ?? 0) < 0) {
            $errors['price_minor'] = 'Price must not be negative.';
        }

        if ((string) ($content['access_policy'] ?? '') === 'free' && (int) ($content['price_minor'] ?? 0) !== 0) {
            $errors['price_minor'] = 'Free content must use zero price.';
        }

        $coverImageUrl = trim((string) ($content['cover_image_url'] ?? ''));
        if ($coverImageUrl !== '' && filter_var($coverImageUrl, FILTER_VALIDATE_URL) === false) {
            $errors['cover_image_url'] = 'Cover image URL is not valid.';
        }

        if (mb_strlen((string) ($content['seo_title'] ?? '')) > 150) {
            $errors['seo_title'] = 'SEO title must not exceed 150 characters.';
        }

        if (mb_strlen((string) ($content['seo_description'] ?? '')) > 255) {
            $errors['seo_description'] = 'SEO description must not exceed 255 characters.';
        }

        return ['errors' => $errors];
    }

    public function validatePart(array $part): array
    {
        $errors = [];

        if (trim((string) ($part['title'] ?? '')) === '') {
            $errors['part_title'] = 'Part title is required.';
        }

        if (trim((string) ($part['body'] ?? '')) === '') {
            $errors['part_body'] = 'Part body is required.';
        }

        $mediaUrl = trim((string) ($part['media_url'] ?? ''));
        if ($mediaUrl !== '' && filter_var($mediaUrl, FILTER_VALIDATE_URL) === false) {
            $errors['part_media_url'] = 'Part media URL is not valid.';
        }

        return ['errors' => $errors];
    }

    public function validateReview(array $review): array
    {
        $errors = [];

        if (!in_array((string) ($review['status'] ?? ''), $this->reviewStatuses, true)) {
            $errors['status'] = 'Review status is not valid.';
        }

        return ['errors' => $errors];
    }
}
