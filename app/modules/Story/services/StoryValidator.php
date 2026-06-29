<?php

declare(strict_types=1);

namespace App\Modules\Story\Services;

final class StoryValidator
{
    public function __construct(
        private readonly array $statuses,
        private readonly array $visibilityOptions,
        private readonly array $languages
    ) {
    }

    public function validateStory(array $story): array
    {
        $errors = [];

        if (trim((string) ($story['title'] ?? '')) === '') {
            $errors['title'] = 'Title is required.';
        }

        if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,119}$/', (string) ($story['slug'] ?? ''))) {
            $errors['slug'] = 'Slug must be 3-120 chars using lowercase letters, numbers, or hyphen.';
        }

        if (trim((string) ($story['summary'] ?? '')) === '') {
            $errors['summary'] = 'Summary is required.';
        }

        if (trim((string) ($story['body'] ?? '')) === '') {
            $errors['body'] = 'Body is required.';
        }

        if (!in_array((string) ($story['visibility'] ?? ''), $this->visibilityOptions, true)) {
            $errors['visibility'] = 'Visibility is not valid.';
        }

        if (!in_array((string) ($story['language'] ?? ''), $this->languages, true)) {
            $errors['language'] = 'Language is not valid.';
        }

        $cover = trim((string) ($story['cover'] ?? ''));
        if ($cover !== '' && filter_var($cover, FILTER_VALIDATE_URL) === false) {
            $errors['cover'] = 'Cover URL is not valid.';
        }

        $ogImage = trim((string) ($story['og_image'] ?? ''));
        if ($ogImage !== '' && filter_var($ogImage, FILTER_VALIDATE_URL) === false) {
            $errors['og_image'] = 'Open Graph image URL is not valid.';
        }

        return ['errors' => $errors];
    }

    public function validateStatus(string $status): array
    {
        $errors = [];

        if (!in_array($status, $this->statuses, true)) {
            $errors['status'] = 'Story status is not valid.';
        }

        return ['errors' => $errors];
    }

    public function validateScheduleAt(string $scheduleAt): array
    {
        $errors = [];

        if (trim($scheduleAt) === '') {
            $errors['published_at'] = 'Schedule date is required.';
        }

        if ($scheduleAt !== '' && strtotime($scheduleAt) === false) {
            $errors['published_at'] = 'Schedule date is not valid.';
        }

        return ['errors' => $errors];
    }
}
