<?php

namespace App;

use DateTimeImmutable;

interface TournamentSource
{
    public function discoverEvents(
        DateTimeImmutable $since
    ): iterable;

    public function fetchEvent(
        string $externalId
    ); //: ?TournamentData;
}
