<?php

namespace App\Services\Mtgo;

use Illuminate\Support\Facades\Http;

final class MtgoClient
{
    public function fetchEventPage(string $url): string
    {
        return Http::timeout(15)
            ->withHeaders([
                'User-Agent' => 'Cardboard/1.0',
            ])
            ->retry(3, 1000)
            ->get($url)
            ->throw()
            ->body();
    }

    // public function getEvent(string $eventId): array
    // {
    //     $response = Http::accept('application/json')
    //         ->withHeaders([
    //             'User-Agent' => 'Cardboard/1.0',
    //         ])->get("https://mtgox.com/api/events/{$eventId}");
    //     return $response->json();
    // }
}
