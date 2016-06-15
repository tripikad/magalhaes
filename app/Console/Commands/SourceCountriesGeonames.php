<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceCountriesGeonames extends Source
{

    protected $signature = 'source:countries_geonames';

    public function handle()
    {

        $sourcename = 'countries_geonames';
        $sourceurl = 'http://api.geonames.org/countryInfo?lang=et&username=kristjanjansen';

        $this->cleanSource($sourcename);
        
        $data = $this->fetchXML($sourceurl, true)['country'];

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