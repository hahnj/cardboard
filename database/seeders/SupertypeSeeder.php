<?php

namespace Database\Seeders;

use App\Models\Supertype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupertypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cardSupertypes = [
            ['name' => 'Basic'],
            // ['name' => 'Elite'],
            ['name' => 'Legendary'],
            // ['name' => 'Ongoing'],
            ['name' => 'Snow'],
            ['name' => 'Token', 'is_playable' => false],
            ['name' => 'World'],
        ];

        foreach ($cardSupertypes as $supertype) {
            Supertype::create($supertype);
        }
    }
}
