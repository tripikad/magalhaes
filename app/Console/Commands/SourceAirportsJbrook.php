<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAirportsJbrook extends Source
{

    protected $signature = 'source:airports_jbrook';

    public function handle()
    {
        $sourcename = 'airports_jbrook';
        $sourceurl = 'https://raw.githubusercontent.com/jbrooksuk/JSON-Airports/master/airports.json';

        $this->cleanSource($sourcename);
        $data = $this->fetchJson($sourceurl);
        
        $this->output->progressStart(count($data));

        collect($data)->each(function($row) use ($sourcename) {

            if (isset($row->lat)) { $row->_lat = $row->lat; }
            if (isset($row->lon)) { $row->_lng = $row->lon; }

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