<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\CarbonImmutable;

// use Spatie\LaravelData\Data;

final readonly class EventData
{
    /**
     * @param  list<DeckData>  $decks
     */
    public function __construct(
        public string $externalId,
        public string $source, // provider
        public string $name,
        public string $format,
        public array $decks, // array of DeckData objects
        public CarbonImmutable $startDate,
        public ?CarbonImmutable $endDate = null, // nullable for single-day events
        public ?string $sourceUrl = null,
    ) {}

}
