<?php

namespace App\Contracts;

use App\Data\EventData;

interface MtgoPayloadParser
{
    public function supports(array $payload): bool;

    public function parse(array $payload): EventData;
}
