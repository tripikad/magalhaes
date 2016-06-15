<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAirportsMwgg extends Source
{

    protected $signature = 'source:airports_mwgg';

    public function handle()
    {
        $sourcename = 'airports_mwgg';
        $sourceurl = 'https://raw.githubusercontent.com/mwgg/Airports/master/airports.json';

        $this->cleanSource($sourcename);
        $data = $this->fetchJson($sourceurl);        

        $this->output->progressStart(count((array)$data));

        collect($data)->each(function($row) use ($sourcename) {

            $row->_lat = $row->lat;
            $row->_lng = $row->lon;

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