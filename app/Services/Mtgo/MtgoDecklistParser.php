<?php

namespace App\Services\Mtgo;

use App\Contracts\MtgoPayloadParser;
use App\Data\EventData;
use RuntimeException;

final class MtgoDecklistParser
{
    protected array $parsers;

    public function __construct(MtgoPayloadParser ...$parsers)
    {
        $this->parsers = $parsers;
    }

    public function parse(string $raw): EventData
    {
        $payload = $this->decodePayload($raw);

        foreach ($this->parsers as $parser) {
            if ($parser->supports($payload)) {
                return $parser->parse($payload);
            }
        }

        throw new RuntimeException('Unsupported MTGO Payload');
    }

    protected function decodePayload(string $raw): array
    {
        $json = $this->extractJson($raw);

        // Extract and decode the JSON.
        return json_decode(
            $json,
            true,
            flags: JSON_THROW_ON_ERROR) ?? false;

    }

    protected function extractJson(string $raw): string
    {
        // Use regex to find the JSON assignment in the HTML.
        if (! preg_match('/window\.MTGO\.decklists\.data\s*=\s*(\{.*?\})\s*;/s', $raw, $matches) || count($matches) < 2 || empty($matches[1])) {
            throw new RuntimeException('Tournament data not found in the provided HTML.');
        }

        // reject any non-JSON content and return the JSON string.
        if (! json_validate($matches[1])) {
            throw new \JsonException('Invalid JSON data found in the provided HTML.');
        }

        return $matches[1];
    }
}
