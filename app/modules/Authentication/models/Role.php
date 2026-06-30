<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Models;

final class Role
{
    public function __construct(
        public int $id,
        public string $slug,
        public string $name,
        public ?string $description
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) $row['slug'],
            (string) $row['name'],
            isset($row['description']) ? (string) $row['description'] : null
        );
    }
}
