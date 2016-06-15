<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAirportsRam extends Source
{

    protected $signature = 'source:airports_ram';

    public function handle()
    {
        $sourcename = 'airports_ram';

        $sourceurl = 'https://raw.githubusercontent.com/ram-nadella/airport-codes/master/airports.json';

        $this->cleanSource($sourcename);
        $data = $this->fetchJson($sourceurl);
        
        $this->output->progressStart(count((array)$data));

        collect($data)->each(function($row) use ($sourcename) {

            $row->_lat = $row->latitude;
            $row->_lng = $row->longitude;

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