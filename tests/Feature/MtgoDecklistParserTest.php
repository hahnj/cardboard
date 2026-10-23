<?php

use Illuminate\Support\Facades\Storage;

test('parses decklist', function () {
    $parser = new App\Services\Mtgo\MtgoDecklistParser();
    $sampleHtml = Storage::disk('local')->get('sample_pauper_league.html');
    $result = $parser->parse($sampleHtml);

    expect($result)->toBeObject();
});
