<?php

namespace App\Services\Mtgo;

use App\Data\DeckData;
use App\Data\EventData;
use App\Data\PlayerData;
use App\EventSource;
use Carbon\CarbonImmutable;
use Override;

final class MtgoLeagueParser extends AbstractMtgoPayloadParser
{
    #[Override]
    public function supports(array $payload): bool
    {
        return array_key_exists('playeventid', $payload)
            && array_key_exists('name', $payload)
            && array_key_exists('publish_date', $payload);
    }

    #[Override]
    public function parse(array $payload): EventData
    {
        $decks = $this->parseDecklists($payload['decklists']) ?? [];

        return new EventData(
            externalId: $payload['playeventid'] ?? '',
            source: EventSource::MTGO->value,
            name: $payload['name'] ?? '',
            format: $payload['format'] ?? '',
            decks: $decks,
            startDate: new CarbonImmutable($payload['publish_date'] ?? ''),
            sourceUrl: $payload['url'] ?? null,
        );
    }

    protected function parseDecklists(array $decklists): array
    {
        return array_map(
            fn (array $deck): DeckData => new DeckData(
                externalId: $deck['loginplayeventcourseid'] ?? '',
                player: new PlayerData(
                    name: $deck['player'] ?? '',
                    externalId: $deck['loginid'] ?? null
                ),
                mainboard: $this->parseCards($deck['main_deck'] ?? []),
                sideboard: $this->parseCards($deck['sideboard_deck'] ?? [])
            ),
            $decklists
        );
    }
}
