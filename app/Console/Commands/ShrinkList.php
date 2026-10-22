<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;


#[Signature('app:shrink-list')]
#[Description('Command description')]
class ShrinkList extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $contents = Storage::get('list.txt');
        $final = [];
        $duplicates = [];
        $rows = explode("\n", $contents);
        foreach($rows as $row) {
            $parts = explode(' ', $row);
            if (count($parts) < 2) {
                continue;
            }
            $count = $parts[0];
            // drop the last 2 parts
            $name = implode(' ', array_slice($parts, 1));
            if(isset($final[$name])) {
                $final[$name] += $count;
                echo "Duplicate: $name\n";
                $duplicates[] = $name;
                continue;
            }
            $final[$name] = $count;
        }
        ksort($final);
        echo "Found " . count($duplicates) . " duplicates\n";
        Storage::put('shrunken_list.txt', implode("\n", array_map(fn($k, $v) => "$v $k", array_keys($final), $final)));
    }
}
