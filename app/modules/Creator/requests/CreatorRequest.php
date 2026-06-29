<?php

declare(strict_types=1);

namespace App\Modules\Creator\Requests;

use App\Core\Modular\BaseRequest;

final class CreatorRequest extends BaseRequest
{
    public function userId(): int
    {
        $auth = $this->session()->get('auth', []);

        return (int) ($auth['user_id'] ?? 0);
    }

    public function handle(): string
    {
        return strtolower(trim((string) $this->input('handle', '')));
    }

    public function slug(): string
    {
        return strtolower(trim((string) $this->input('slug', '')));
    }

    public function displayName(): string
    {
        return $this->string('display_name');
    }

    public function profileData(): array
    {
        return [
            'handle' => $this->handle(),
            'slug' => $this->slug(),
            'display_name' => $this->displayName(),
            'creator_type' => $this->string('creator_type'),
            'tagline' => $this->string('tagline'),
            'bio' => $this->string('bio'),
            'category' => $this->string('category'),
            'location' => $this->string('location'),
            'avatar_url' => $this->string('avatar_url'),
            'cover_image_url' => $this->string('cover_image_url'),
            'website_url' => $this->string('website_url'),
            'public_email' => strtolower($this->string('public_email')),
            'seo_title' => $this->string('seo_title'),
            'seo_description' => $this->string('seo_description'),
        ];
    }

    public function categories(): array
    {
        return $this->normalizeListInput($this->input('categories', ''));
    }

    public function skills(): array
    {
        return $this->normalizeListInput($this->input('skills', ''));
    }

    public function search(): string
    {
        return $this->string('search');
    }

    public function page(): int
    {
        $page = (int) $this->query('page', 1);

        return $page > 0 ? $page : 1;
    }

    public function perPage(): int
    {
        $perPage = (int) $this->query('per_page', 10);
        if ($perPage < 1) {
            return 10;
        }

        return min($perPage, 50);
    }

    public function statusFilter(): string
    {
        return $this->string('status');
    }

    public function categoryFilter(): string
    {
        return $this->string('category');
    }

    public function includeDeleted(): bool
    {
        return $this->boolean('include_deleted');
    }

    public function applicationData(): array
    {
        return [
            'application_note' => $this->string('application_note'),
            'kyc_full_name' => $this->string('kyc_full_name'),
            'kyc_document_type' => $this->string('kyc_document_type'),
            'kyc_document_number' => $this->string('kyc_document_number'),
            'kyc_document_url' => $this->string('kyc_document_url'),
        ];
    }

    public function socialLinkData(): array
    {
        return [
            'platform' => $this->string('platform'),
            'url' => $this->string('url'),
        ];
    }

    public function portfolioData(): array
    {
        return [
            'portfolio_type' => $this->string('portfolio_type'),
            'title' => $this->string('title'),
            'summary' => $this->string('summary'),
            'organization' => $this->string('organization'),
            'issued_at' => $this->string('issued_at'),
            'ended_at' => $this->string('ended_at'),
            'url' => $this->string('url'),
            'thumbnail_url' => $this->string('thumbnail_url'),
            'is_featured' => $this->boolean('is_featured'),
        ];
    }

    public function settingsData(): array
    {
        return [
            'public_page_enabled' => $this->boolean('public_page_enabled'),
            'allow_public_contact' => $this->boolean('allow_public_contact'),
            'show_portfolio_publicly' => $this->boolean('show_portfolio_publicly'),
            'seo_title' => $this->string('seo_title'),
            'seo_description' => $this->string('seo_description'),
        ];
    }

    public function achievementData(): array
    {
        return [
            'title' => $this->string('achievement_title'),
            'issuer' => $this->string('achievement_issuer'),
            'description' => $this->string('achievement_description'),
            'achieved_at' => $this->string('achievement_date'),
            'url' => $this->string('achievement_url'),
        ];
    }

    public function statisticsData(): array
    {
        return [
            'followers_count' => $this->integer('followers_count'),
            'reads_count' => $this->integer('reads_count'),
            'listens_count' => $this->integer('listens_count'),
            'downloads_count' => $this->integer('downloads_count'),
            'sponsor_count' => $this->integer('sponsor_count'),
            'donation_count' => $this->integer('donation_count'),
            'affiliate_count' => $this->integer('affiliate_count'),
            'revenue_minor' => $this->integer('revenue_minor'),
            'wallet_available_minor' => $this->integer('wallet_available_minor'),
            'wallet_pending_minor' => $this->integer('wallet_pending_minor'),
        ];
    }

    public function badges(): array
    {
        return $this->normalizeListInput($this->input('badges', []));
    }

    public function reviewData(): array
    {
        return [
            'status' => $this->string('status'),
            'verification_status' => $this->string('verification_status'),
            'creator_level' => $this->string('creator_level'),
            'creator_rank' => $this->integer('creator_rank'),
            'review_notes' => $this->string('review_notes'),
            'badges' => $this->badges(),
            'statistics' => $this->statisticsData(),
            'reviewed_by_user_id' => $this->userId(),
        ];
    }

    public function applicationId(): int
    {
        return (int) $this->route('id', 0);
    }

    public function itemId(): int
    {
        return (int) $this->route('id', 0);
    }

    public function creatorId(): int
    {
        return (int) $this->route('id', 0);
    }

    private function normalizeListInput(mixed $value): array
    {
        if (is_array($value)) {
            $items = $value;
        } else {
            $normalized = str_replace(["\r\n", "\r"], "\n", (string) $value);
            $items = preg_split('/[\n,]+/', $normalized) ?: [];
        }

        $items = array_map(
            static fn (mixed $item): string => trim((string) $item),
            $items
        );

        return array_values(array_unique(array_filter($items, static fn (string $item): bool => $item !== '')));
    }
}
