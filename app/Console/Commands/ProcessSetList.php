<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Set;

#[Signature('app:process-set-list')]
#[Description('Command description')]
class ProcessSetList extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $contents = Storage::get('setList.txt');
        $setCatalog = Set::all();
        // Process the set list contents
        $rows = explode("\n", $contents);
        $sets = [];
        foreach($rows as $row) {
            // Process each row
            $parts = explode(', ', $row);
            foreach($parts as &$part) {
                $part = trim($part);
                $sets[] = $part;
            }
        }
        // process sets to be unique with a count of how many times they appear
        $final = [];
        foreach($sets as $set) {
            if(isset($final[$set])) {
                $final[$set]++;
            } else {
                $final[$set] = 1;
            }
        }
        ksort($final);
        Storage::put('processed_set_list.txt', implode(
            "\n",
            array_map(fn($k, $v) => "$v $k",
            array_keys($final), $final))
        );
        echo "Processed " . count($final) . " unique sets\n";
    }
}
