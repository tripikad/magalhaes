<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceTripDestinations extends Source
{

    protected $signature = 'source:trip_destinations';

    public function handle()
    {

        $sourcename = 'trip_destinations';
        $sourceurl = 'http://localhost:8000/api/destinations';

        $this->cleanSource($sourcename);
        $data = $this->fetchJson($sourceurl);

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