<?php

namespace App\Services\Mtgo;

final readonly class MtgoTournamentImporter
{
    public function __construct(
        private MtgoClient $client,
        private MtgoDecklistParser $parser,
    ) {
    }

    public function import(string $url): array
    {
        $html = $this->client->fetchTournamentPage($url);
        return $this->parser->parse($html);
    }
}
