<?php

declare(strict_types=1);

namespace App\Modules\Creator\Dto;

use App\Modules\Creator\Requests\CreatorRequest;

final class CreatorSearchCriteria
{
    public function __construct(
        public readonly string $search,
        public readonly string $status,
        public readonly string $category,
        public readonly int $page,
        public readonly int $perPage,
        public readonly bool $includeDeleted,
        public readonly bool $publicOnly
    ) {
    }

    public static function fromRequest(CreatorRequest $request, bool $publicOnly = false): self
    {
        return new self(
            $request->search(),
            $request->statusFilter(),
            $request->categoryFilter(),
            $request->page(),
            $request->perPage(),
            $request->includeDeleted(),
            $publicOnly
        );
    }

    public function offset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }
}
