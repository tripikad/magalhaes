<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAirports2 extends Command
{

    protected $signature = 'source:airports_2';

    public function handle()
    {
        $sourcename = 'airports2';

        $source = 'https://raw.githubusercontent.com/ram-nadella/airport-codes/master/airports.json';

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