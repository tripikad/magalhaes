<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceCountriesTrip extends Source
{

    protected $signature = 'source:countries_trip';

    public function handle()
    {

        $sourcename = 'countries_trip';
        $sourceurl = 'http://trip.ee/api/destinations';

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