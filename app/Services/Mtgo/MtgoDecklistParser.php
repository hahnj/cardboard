<?php

namespace App\Services\Mtgo;

use App\Data\DeckCardData;
use App\Data\DeckData;
use App\Data\EventData;
use App\Data\PlayerData;
use App\EventSource;
use Carbon\CarbonImmutable;

final class MtgoDecklistParser
{
    public function parse(string $raw): EventData
    {
        // Find the embedded JSON assignment:
        $json = $this->extractJson($raw);

        // Extract and decode the JSON.
        $data = json_decode($json, true);

        $decks = $this->parseDecklists($data['decklists'] ?? []);

        // Return normalized tournament data.
        return new EventData(
            externalId: $data['event_id'] ?? '',
            source: EventSource::MTGO->value,
            name: $data['description'] ?? '',
            format: $data['format'] ?? '',
            decks: $decks,
            startDate: new CarbonImmutable($data['start_date'] ?? ''),
            sourceUrl: $data['url'] ?? null,
        );
    }

    protected function extractJson(string $raw): string
    {
        // Use regex to find the JSON assignment in the HTML.
        if (! preg_match('/window\.MTGO\.decklists\.data\s*=\s*(\{.*?\})\s*;/s', $raw, $matches) || count($matches) < 2 || empty($matches[1])) {
            throw new \RuntimeException('Tournament data not found in the provided HTML.');
        }

        // reject any non-JSON content and return the JSON string.
        if (! json_validate($matches[1])) {
            throw new \JsonException('Invalid JSON data found in the provided HTML.');
        }

        return $matches[1];
    }

    protected function parseDecklists(array $decklists): array
    {
        return array_map(
            fn (array $deck) => new DeckData(
                externalId: $deck['deck_id'] ?? '',
                player: new PlayerData(
                    name: $deck['player'] ?? '',
                    externalId: $deck['loginid'] ?? null
                ),
                mainboard: $this->parseDeckCardData($deck['main_deck'] ?? []),
                sideboard: $this->parseDeckCardData($deck['sideboard_deck'])
            ),
            $decklists
        );
    }

    protected function parseDeckCardData(array $cards): array
    {
        return array_map(fn (array $card) => new DeckCardData(
            name: $card['card_attributes']['card_name'],
            quantity: (int) $card['qty'],
            externalId: isset($card['card_attributes']['digitalobjectcatalogid'])
                ? (string) $card['card_attributes']['digitalobjectcatalogid']
                : null,
        ), $cards);
    }
}
