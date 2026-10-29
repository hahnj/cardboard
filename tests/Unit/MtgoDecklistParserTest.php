<?php

declare(strict_types=1);

use App\Data\DeckCardData;
use App\Data\DeckData;
use App\Data\EventData;
use App\Data\PlayerData;
use App\Services\Mtgo\MtgoChallengeParser;
use App\Services\Mtgo\MtgoDecklistParser;
use App\Services\Mtgo\MtgoLeagueParser;

function mtgoFixtureHtml(string $type): string
{
    // Read the fixture JSON file and embed it in a script tag to simulate the MTGO decklist data.

    $fixturePath = "tests/Fixtures/mtgo/{$type}.json";
    $json = file_get_contents($fixturePath);

    if ($json === false) {
        throw new RuntimeException('Failed to read fixture file.');
    }

    return "<script>window.MTGO.decklists.data = {$json};</script>";
}

function mtgoParser(): MtgoDecklistParser
{
    return new MtgoDecklistParser(
        new MtgoChallengeParser,
        new MtgoLeagueParser
    );
}

it('parses format from MTGO event fixtures', function (
    string $fixture,
    string $expectedFormat
) {
    $json = file_get_contents(
        dirname(__DIR__)."/Fixtures/mtgo/{$fixture}"
    );

    $event = mtgoParser()->parse(
        "<script>window.MTGO.decklists.data = {$json};</script>"
    );

    expect($event->format)->toBe($expectedFormat);
})->with([
    'Pauper Challenge' => [
        'pauper-challenge.json',
        'CPAUPER',
    ],
]);

it('uses an empty format when the MTGO fixture omits it', function () {
    $event = mtgoParser()->parse(
        mtgoFixtureHtml('pauper-league')
    );

    expect($event->format)->toBe('');
});

it('parses a Pauper Challenge event', function () {
    $event = mtgoParser()->parse(mtgoFixtureHtml('pauper-challenge'));
    expect($event)->toBeInstanceOf(EventData::class)
        ->externalId->toBe('12856975')
        ->name->toBe('Pauper Challenge 16')
        ->format->toBe('CPAUPER')
        ->startDate->toDateString()->toBe('2026-10-24');

    $deck = collect($event->decks)
        ->first(
            fn (DeckData $deck) => $deck->player->name === '_Batutinha_'
        );

    expect($deck)
        ->toBeInstanceOf(DeckData::class)
        ->externalId->toBe('58700741');
    expect($deck->player)
        ->toBeInstanceOf(PlayerData::class)
        ->externalId->toBe(453404)
        ->name->toBe('_Batutinha_');

    $spellPierce = collect($deck->mainboard)
        ->first(fn (DeckCardData $card) => $card->name === 'Spell Pierce');
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

it('parses a Pauper League event', function () {
    $event = mtgoParser()->parse(mtgoFixtureHtml('pauper-league'));
    expect($event)->toBeInstanceOf(EventData::class)
        ->externalId->toBe('11153')
        ->name->toBe('Pauper League')
        ->format->toBe('')
        ->startDate->toDateString()->toBe('2026-10-27');

    $deck = collect($event->decks)
        ->first(
            fn (DeckData $deck) => $deck->player->name === 'Xavier_Armer'
        );

    expect($deck)
        ->toBeInstanceOf(DeckData::class)
        ->externalId->toBe('36181365');
    expect($deck->player)
        ->toBeInstanceOf(PlayerData::class)
        ->externalId->toBe(2917831)
        ->name->toBe('Xavier_Armer');

    $almsOfTheVein = collect($deck->mainboard)
        ->first(fn (DeckCardData $card) => $card->name === 'Alms of the Vein');
    expect($almsOfTheVein)
        ->toBeInstanceOf(DeckCardData::class)
        ->quantity->toBe(4)
        ->externalId->toBe('60310');

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
    $parser = mtgoParser();

    $parser->parse('<html>No decklists here</html>');
})->throws(
    RuntimeException::class,
    'Tournament data not found'
);

it('rejects malformed MTGO JSON', function () {
    $parser = mtgoParser();

    $parser->parse(
        '<script>window.MTGO.decklists.data = {invalid};</script>'
    );
})->throws(
    JsonException::class,
    'Invalid JSON data found in the provided HTML.'
);
