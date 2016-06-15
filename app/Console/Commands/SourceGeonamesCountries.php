<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceGeonamesCountries extends Source
{

    protected $signature = 'source:geonames_countries';

    public function handle()
    {

        $sourcename = 'geonames_countries';
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