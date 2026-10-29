<?php

namespace App\Services\Mtgo;

use App\Data\DeckData;
use App\Data\EventData;
use App\Data\PlayerData;
use App\EventSource;
use Carbon\CarbonImmutable;
use Override;

final class MtgoChallengeParser extends AbstractMtgoPayloadParser
{
    #[Override]
    public function supports(array $payload): bool
    {
        return array_key_exists('event_id', $payload)
            && array_key_exists('description', $payload)
            && array_key_exists('starttime', $payload);
    }

    #[Override]
    public function parse(array $payload): EventData
    {
        $decks = $this->parseDecklists($payload['decklists']) ?? [];

        return new EventData(
            externalId: $payload['event_id'] ?? '',
            source: EventSource::MTGO->value,
            name: $payload['description'] ?? '',
            format: $payload['format'] ?? '',
            decks: $decks,
            startDate: new CarbonImmutable($payload['starttime'] ?? ''),
            sourceUrl: $payload['url'] ?? null,
        );
    }

    protected function parseDecklists(array $decklists): array
    {
        return array_map(
            fn (array $deck): DeckData => new DeckData(
                externalId: $deck['decktournamentid'] ?? '',
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
