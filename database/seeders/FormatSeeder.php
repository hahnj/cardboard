<?php

namespace Database\Seeders;

use App\FormatCategory;
use App\Models\Format;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formats = [
            ['name' => 'Standard', 'category' => FormatCategory::constructed],
            ['name' => 'Modern', 'category' => FormatCategory::constructed, 'is_eternal' => true],
            ['name' => 'Pioneer', 'category' => FormatCategory::constructed, 'is_eternal' => true],
            ['name' => 'Legacy', 'category' => FormatCategory::constructed, 'is_eternal' => true],
            ['name' => 'Vintage', 'category' => FormatCategory::constructed, 'is_eternal' => true],
            ['name' => 'Commander', 'category' => FormatCategory::constructed, 'is_eternal' => true],
            ['name' => 'Pauper', 'category' => FormatCategory::constructed, 'is_eternal' => true],
            ['name' => 'Historic', 'category' => FormatCategory::constructed],
            ['name' => 'Sealed', 'category' => FormatCategory::limited],
            ['name' => 'Draft', 'category' => FormatCategory::limited],
        ];

        foreach ($formats as $format) {
            Format::create($format);
        }
    }
}
