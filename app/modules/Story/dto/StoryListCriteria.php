<?php

declare(strict_types=1);

namespace App\Modules\Story\Dto;

use App\Modules\Story\Requests\StoryRequest;

final class StoryListCriteria
{
    public function __construct(
        public readonly string $search,
        public readonly string $status,
        public readonly string $visibility,
        public readonly int $page,
        public readonly int $perPage,
        public readonly bool $includeDeleted,
        public readonly bool $publicOnly,
        public readonly ?int $creatorId = null
    ) {
    }

    public static function fromRequest(StoryRequest $request, bool $publicOnly = false, ?int $creatorId = null): self
    {
        return new self(
            $request->search(),
            $request->statusFilter(),
            $request->visibilityFilter(),
            $request->page(),
            $request->perPage(),
            $request->includeDeleted(),
            $publicOnly,
            $creatorId
        );
    }

    public function offset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }
}
