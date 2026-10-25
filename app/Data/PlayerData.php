<?php

namespace App\Data;

final readonly class PlayerData
{
    public function __construct(
        public string $name,
        public ?int $externalId = null,
    ) {}
}
