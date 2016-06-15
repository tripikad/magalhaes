<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceCountriesMledoze extends Source
{

    protected $signature = 'source:countries_mledoze';

    public function handle()
    {

        $sourcename = 'countries_mledoze';
        $sourceurl = 'https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json';
        
        $this->cleanSource($sourcename);
        $data = $this->fetchJson($sourceurl);
        
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