<?php

declare(strict_types=1);

namespace App\Data;

final readonly class DeckData
{
    /**
     * @param list<DeckCardData> $mainboard
     * @param list<DeckCardData> $sideboard
     */
    public function __construct(
        public string $playerName,
        public string $name,
        public string $format,
        public array $mainboard,
        public array $sideboard,
        public ?int $placement = null,
        public ?int $wins = null,
        public ?int $losses = null,
        public ?int $draws = null,
        public ?string $externalId = null,
    ) {}
}
