<?php

namespace App\Services\Mtgo;

final class MtgoDecklistParser
{
    public function parse(string $raw): array
    {
        // Find the embedded JSON assignment:
        // window.MTGO.decklists.data = {...};

        // Extract and decode the JSON.

        // Return normalized tournament data.
        return [];
    }
}
