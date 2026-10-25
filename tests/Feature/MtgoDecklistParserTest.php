<?php

use App\Data\EventData;
use App\Services\Mtgo\MtgoDecklistParser;
use Illuminate\Support\Facades\Storage;

test('parser throws exception when tournament data is not found', function () {
    $parser = new MtgoDecklistParser;
    $sampleHtml = Storage::disk('public')->get('sample_pauper_league.html');
    $parser->parse($sampleHtml);
})->throws(RuntimeException::class, 'Tournament data not found in the provided HTML.');

test('parser extracts tournament data from sample HTML', function () {
    $parser = new MtgoDecklistParser;
    $sampleHtml = Storage::disk('public')->get('Pauper Challenge 16 _ Decklists _ Magic_ The Gathering Online.html');
    $data = $parser->parse($sampleHtml);

    expect($data)->toBeInstanceOf(EventData::class);
    expect($data->externalId)->toBeString();
    expect($data->decks)->toBeArray();
});
