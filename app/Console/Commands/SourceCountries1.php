<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceCountries1 extends Command
{

    protected $signature = 'source:countries1';

    public function handle()
    {
        $sourcename = 'countries1';

        $source = 'https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json';

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
            
            if (isset($row->latlng[0])) {
                $row->_lat = $row->latlng[0];
                $row->_lng = $row->latlng[1];
            }

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