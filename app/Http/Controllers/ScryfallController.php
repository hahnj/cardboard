<?php

namespace App\Http\Controllers;

use App\ImageFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScryfallController extends Controller
{
    public function testRequest()
    {
        $response = Http::accept('image')
        ->withHeaders([
            'User-Agent' => 'Cardboard/1.0',
        ])->get('https://api.scryfall.com/cards/named', [
            'exact' => 'Black Lotus',
        ]);
        return $response->json();
    }

    public function getCardByName(string $name)
    {
        $response = Http::accept('application/json')
        ->withHeaders([
            'User-Agent' => 'Cardboard/1.0',
        ])->get('https://api.scryfall.com/cards/named', [
            'exact' => $name,
        ]);
        return $response->json();
    }

    public function getCardImageByName(string $name, ImageFormat $format = ImageFormat::large)
    {
        $key = 'scryfall_card_image_cache:' . $name . ':' . $format->value;
        if (cache()->has($key)) {
            return cache()->get($key);
        }
        $response = Http::accept('image')
        ->withHeaders([
            'User-Agent' => 'Cardboard/1.0',
        ])->get('https://api.scryfall.com/cards/named', [
            'exact' => $name,
            'format' => $format->value,
        ]);
        cache()->put($key, $response->body(), now()->addHour());
        return $response->body();
    }

    public function updateCatalogTables()
    {
        $this->updateSets();
        $this->updateKeywords();
        $this->updateArtists();
    }

    public function updateSeededTables()
    {
        $this->updateTypes();
        $this->updateSupertypes();
    }

    public function updateSets()
    {
        $response = Http::accept('application/json')
        ->withHeaders([
            'User-Agent' => 'Cardboard/1.0',
        ])->get('https://api.scryfall.com/sets');
        if(count($response->json()['data']) === \App\Models\Set::count()) {
            return 'Sets are up to date';
        }
        foreach($response->json()['data'] as $set) {
            \App\Models\Set::updateOrCreate([
                'code' => $set['code'],
            ], [
                'name' => $set['name'],
                'release' => $set['released_at'],
            ]);
        }
        return $response->json();
    }

    public function updateTypes()
    {
        $response = Http::accept('application/json')
        ->withHeaders([
            'User-Agent' => 'Cardboard/1.0',
        ])->get('https://api.scryfall.com/card-types');
        if($response->json()['total_values'] === \App\Models\Type::count()) {
            return 'Types are up to date';
        }
        foreach($response->json()['data'] as $type) {
            \App\Models\Type::updateOrCreate([
                'name' => $type['name'],
            ]);
        }
        return $response->json();
    }

    public function updateSupertypes()
    {
        $response = Http::accept('application/json')
        ->withHeaders([
            'User-Agent' => 'Cardboard/1.0',
        ])->get('https://api.scryfall.com/supertypes');
        if($response->json()['total_values'] === \App\Models\Supertype::count()) {
            return 'Supertypes are up to date';
        }
        foreach($response->json()['data'] as $supertype) {
            \App\Models\Supertype::updateOrCreate([
                'name' => $supertype['name'],
            ]);
        }
        return $response->json();
    }

    public function updateKeywords()
    {
        $response = Http::accept('application/json')
        ->withHeaders([
            'User-Agent' => 'Cardboard/1.0',
        ])->get('https://api.scryfall.com/keyword-abilities');
        if($response->json()['total_values'] === \App\Models\Keyword::count()) {
            return 'Keywords are up to date';
        }
        foreach($response->json()['data'] as $keyword) {
            \App\Models\Keyword::updateOrCreate([
                'name' => $keyword['name'],
            ], [
                'description' => $keyword['description'],
            ]);
        }
        return $response->json();
    }

    public function updateArtists()
    {
        $response = Http::accept('application/json')
        ->withHeaders([
            'User-Agent' => 'Cardboard/1.0',
        ])->get('https://api.scryfall.com/artists');
        if($response->json()['total_values'] === \App\Models\Artist::count()) {
            return 'Artists are up to date';
        }
        foreach($response->json()['data'] as $artist) {
            \App\Models\Artist::updateOrCreate([
                'name' => $artist['name'],
            ]);
        }
        return $response->json();
    }

    // get bulk data from scryfall and update the cards table
}
