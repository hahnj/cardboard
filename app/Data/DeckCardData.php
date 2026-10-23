<?php

declare(strict_types=1);

namespace App\Data;

final readonly class DeckCardData
{
    public function __construct(
        public string $name,
        public int $quantity,
        public ?string $externalId = null,
    ) {
        if ($quantity < 1) {
            throw new \InvalidArgumentException(
                'Card quantity must be positive.'
            );
        }
    }
}
