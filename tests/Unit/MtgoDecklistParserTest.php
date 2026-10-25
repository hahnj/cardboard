<?php

declare(strict_types=1);

use App\Data\DeckCardData;
use App\Data\DeckData;
use App\Data\EventData;
use App\Data\PlayerData;
use App\Services\Mtgo\MtgoDecklistParser;

function mtgoFixtureHtml(): string
{
    // Read the fixture JSON file and embed it in a script tag to simulate the MTGO decklist data.

    $fixturePath = 'tests/Fixtures/mtgo/pauper-challenge.json';
    $json = file_get_contents($fixturePath);

    if ($json === false) {
        throw new RuntimeException('Failed to read fixture file.');
    }

    return "<script>window.MTGO.decklists.data = {$json};</script>";
}

it('parses MTGO event fixtures', function (
    string $fixture,
    string $expectedFormat
) {
    $json = file_get_contents(
        dirname(__DIR__)."/Fixtures/mtgo/{$fixture}"
    );

    $event = new MtgoDecklistParser()->parse(
        "<script>window.MTGO.decklists.data = {$json};</script>"
    );

    expect($event->format)->toBe($expectedFormat);
})->with([
    'Pauper Challenge' => [
        'pauper-challenge.json',
        'CPAUPER',
    ],
]);

it('parses a Pauper Challenge event', function () {
    $event = new MtgoDecklistParser()->parse(mtgoFixtureHtml());
    expect($event)->toBeInstanceOf(EventData::class)
        ->externalId->toBe('12856975')
        ->name->toBe('Pauper Challenge 16')
        ->format->toBe('CPAUPER')
        ->startDate->toDateString()->toBe('2026-10-25');

    $deck = collect($event->decks)
        ->first(fn (DeckData $deck) => $deck->player->name === '_Batutinha_'
        );

    expect($deck)
        ->toBeInstanceOf(DeckData::class);
    // ->externalId->toBe('58700741')
    expect($deck->player)
        ->toBeInstanceOf(PlayerData::class)
        ->externalId->toBe(453404)
        ->name->toBe('_Batutinha_');

    $spellPierce = collect($deck->mainboard)
        ->first(fn (DeckCardData $card) => $card->name === 'Spell Pierce'
        );
    expect($spellPierce)
        ->toBeInstanceOf(DeckCardData::class)
        ->quantity->toBe(2)
        ->externalId->toBe('81091');

    expect($deck->mainboard)->not->toBeEmpty();
    expect($deck->sideboard)->not->toBeEmpty();

    expect(array_sum(array_map(
        fn (DeckCardData $card) => $card->quantity,
        $deck->mainboard
    )))->toBe(60);

    expect(array_sum(array_map(
        fn (DeckCardData $card) => $card->quantity,
        $deck->sideboard
    )))->toBe(15);
});

it('rejects HTML without MTGO decklist data', function () {
    $parser = new MtgoDecklistParser;

    $parser->parse('<html>No decklists here</html>');
})->throws(
    RuntimeException::class,
    'Tournament data not found'
);

it('rejects malformed MTGO JSON', function () {
    $parser = new MtgoDecklistParser;

    $parser->parse(
        '<script>window.MTGO.decklists.data = {invalid};</script>'
    );
})->throws(
    JsonException::class,
    'Invalid JSON data found in the provided HTML.'
);
