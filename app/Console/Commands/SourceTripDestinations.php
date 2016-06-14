<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceTripDestinations extends Command
{

    protected $signature = 'source:trip_destinations';

    public function handle()
    {
        $sourcename = 'trip_destinations';

        $sourceurl = 'http://localhost:8000/api/destinations';

        $this->line('Cleaning previous data');

        app('db')->table('source')->where('sourcename', '=', $sourcename)->delete();

        $this->line('Downloading source');

        $this->info($sourcename);

        $data = json_decode(file_get_contents($sourceurl));

        $this->line('Inserting data');

        $this->output->progressStart(count((array)$data));

        collect($data)->each(function($row) use ($sourcename) {

            app('db')
                ->table('source')
                ->insert([
                    'sourcename' => $sourcename,
                    'value' => json_encode($row)
                ]);

            $this->output->progressAdvance();

        });

        $this->output->progressFinish();

    }

}