<?php

namespace App\Services\Mtgo;

use App\Data\EventData;

final readonly class MtgoTournamentImporter
{
    public function __construct(
        private MtgoClient $client,
        private MtgoDecklistParser $parser,
    ) {}

    public function import(string $url): EventData
    {
        $html = $this->client->fetchTournamentPage($url);

        return $this->parser->parse($html);
    }
}
