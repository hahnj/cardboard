<?php

namespace App\Services\Mtgo;

use App\Contracts\MtgoPayloadParser;
use App\Data\DeckCardData;
use App\Data\DeckData;

abstract class AbstractMtgoPayloadParser implements MtgoPayloadParser
{
    /**
     * @return array<DeckData>
     */
    abstract protected function parseDecklists(array $decklists): array;

    /**
     * @return array<DeckCardData>
     */
    protected function parseCards(array $cards): array
    {
        return array_map(fn (array $card): DeckCardData => new DeckCardData(
            name: $card['card_attributes']['card_name'],
            quantity: (int) $card['qty'],
            externalId: isset($card['docid'])
                ? (string) $card['docid']
                : null,
        ), $cards);
    }
}
