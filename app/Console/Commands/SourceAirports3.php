<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAirports3 extends Command
{

    protected $signature = 'source:airports3';

    public function handle()
    {
        $sourcename = 'airports3';

        $source = 'https://raw.githubusercontent.com/mwgg/Airports/master/airports.json';

        $this->line('');
        $this->line('Cleaning previous data');

        app('db')->table('source')->where('sourcename', '=', $sourcename)->delete();

        $this->line('Downloading source');
        $this->info($source);
        
        $data = json_decode(
            file_get_contents($source)
        );

        $this->line('Inserting data');

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