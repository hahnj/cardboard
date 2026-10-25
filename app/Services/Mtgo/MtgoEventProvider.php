<?php

namespace App\Services\Mtgo;

use App\Contracts\EventProvider;
use App\Data\DeckCardData;
use App\Data\DeckData;
use App\Data\EventData;
use App\Data\PlayerData;
use App\EventSource;
use Carbon\CarbonImmutable;

final class MtgoEventProvider implements EventProvider
{
    public function discoverEvents(\DateTimeImmutable $since): iterable
    {
        // TODO: Implement actual discovery logic from MTGO API or database.
        return [];
    }

    /**
     * Fetches an event by its external ID.
     * TODO: Implement actual fetching logic from MTGO API or database.
     *
     * @param  string  $externalId  The external ID of the event.
     * @return EventData|null The event data if found, or null if not found.
     */
    public function fetchEvent(string $externalId): ?EventData
    {
        return new EventData(
            externalId: $externalId,
            source: EventSource::MTGO->value,
            name: 'Sample Event',
            format: 'Pauper',
            startDate: new CarbonImmutable('2024-06-01'),
            endDate: new CarbonImmutable('2024-06-02'),
            decks: [
                new DeckData(
                    player: new PlayerData(name: 'Player 1', externalId: 12345),
                    mainboard: [
                        new DeckCardData(name: 'Card A', quantity: 4),
                        new DeckCardData(name: 'Card B', quantity: 2),
                    ],
                    sideboard: [
                        new DeckCardData(name: 'Card C', quantity: 3),
                    ],
                ),
            ],
            sourceUrl: null,
        );
    }

    public function source(): EventSource
    {
        return EventSource::MTGO;
    }
}
