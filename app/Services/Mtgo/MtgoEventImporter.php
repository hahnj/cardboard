<?php

namespace App\Services\Mtgo;

use App\Data\EventData;

final readonly class MtgoEventImporter
{
    public function __construct(
        private MtgoClient $client,
        private MtgoDecklistParser $parser,
    ) {}

    public function import(string $url): EventData
    {
        $html = $this->client->fetchEventPage($url);

        return $this->parser->parse($html);
    }
}
