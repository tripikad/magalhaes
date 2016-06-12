<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAirports extends Command
{

    protected $signature = 'source:airports_1';

    public function handle()
    {
        $source = 'https://raw.githubusercontent.com/jbrooksuk/JSON-Airports/master/airports.json';

        $this->line('');
        $this->line('Cleaning previous data');

        app('db')->table('source')->where('sourcename', '=', 'airports')->delete();

        $this->line('Downloading source');
        $this->info($source);
        
        $data = json_decode(
            file_get_contents($source)
        );

        $this->line('Inserting data');

        $this->output->progressStart(count($data));

        collect($data)->each(function($row) {

            if (isset($row->lat)) { $row->_lat = $row->lat; }
            if (isset($row->lon)) { $row->_lng = $row->lon; }

            app('db')
                ->table('source')
                ->insert([
                    'sourcename' => 'airports',
                    'value' => json_encode($row)
                ]);

            $this->output->progressAdvance();

        });

        $this->output->progressFinish();

    }

}