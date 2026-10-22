<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cardTypes = [
            ['name' => 'Artifact', 'is_permanent' => true],
            ['name' => 'Battle', 'is_permanent' => true],
            // ['name' => 'Boss', 'is_permanent' => true],
            // ['name' => 'Conspiracy', 'is_permanent' => true],
            ['name' => 'Creature', 'is_permanent' => true],
            ['name' => 'Dungeon', 'is_permanent' => true],
            ['name' => 'Emblem'],
            ['name' => 'Enchantment', 'is_permanent' => true],
            // ['name' => 'Event'],
            // ['name' => 'Hero', 'is_permanent' => true],
            ['name' => 'Instant'],
            ['name' => 'Kindred'],
            ['name' => 'Land', 'is_permanent' => true],
            // ['name' => 'Phenomenon'],
            // ['name' => 'Plane', 'is_permanent' => true],
            ['name' => 'Planeswalker', 'is_permanent' => true],
            // ['name' => 'Scheme'],
            ['name' => 'Sorcery'],
            // ['name' => 'Vanguard', 'is_permanent' => true],
        ];

        foreach ($cardTypes as $type) {
            Type::create($type);
        }
    }
}
