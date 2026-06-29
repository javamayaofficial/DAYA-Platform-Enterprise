<?php

declare(strict_types=1);

namespace App\Modules\Content\Dto;

use App\Modules\Content\Requests\ContentRequest;

final class ContentSearchCriteria
{
    public function __construct(
        public readonly string $search,
        public readonly string $status,
        public readonly string $contentType,
        public readonly int $page,
        public readonly int $perPage,
        public readonly bool $includeDeleted,
        public readonly bool $publicOnly,
        public readonly ?int $creatorId = null
    ) {
    }

    public static function fromRequest(ContentRequest $request, bool $publicOnly = false, ?int $creatorId = null): self
    {
        return new self(
            $request->search(),
            $request->statusFilter(),
            $request->contentTypeFilter(),
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
