<?php

namespace App\Contracts;

use DateTimeImmutable;
use App\Data\EventData;
use App\EventSource;

interface EventProvider
{
    public function discoverEvents(
        DateTimeImmutable $since
    ): iterable;

    public function fetchEvent(
        string $externalId
    ): ?EventData;

    public function source(): EventSource;
}
