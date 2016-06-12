<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceTeleportCountries extends Command
{

    protected $signature = 'source:countries_teleport';

    public function handle()
    {

        // Data:

        // Countries    https://api.teleport.org/api/countries/
        // Divisions    https://api.teleport.org/api/countries/iso_alpha2:PT/admin1_divisions
        // Cities       https://api.teleport.org/api/countries/iso_alpha2:PT/admin1_divisions/geonames:17/cities
        // City         https://api.teleport.org/api/cities/geonameid:2735943/
        // City details http://api.geonames.org/get?geonameId=2735943&username=kristjanjansen

        $this->line('');
        $this->line('Cleaning previous data');

        app('db')->table('source')->where('sourcename', '=', 'countries_teleport')->delete();
        
        $this->line('Fetching data');

        
        $sourceurl = 'https://api.teleport.org/api/countries/';
        $sourcename = 'countries_teleport';

        $this->line('Downloading source');
        $this->info($sourceurl);

        $data = json_decode(file_get_contents($sourceurl), true);

        $this->line('Inserting data');
        $this->output->progressStart(count($data));

        collect($data['_links']['country:items'])->map(function($country) {
     
            $country['_key'] = collect(explode('/', $country["href"]))->take(-2)->first();

            return $country;
     
        })->each(function($country) use ($sourcename) {

            $row = [
                '_key' => $country['_key'],
                'name' => $country['name'],
            ];

            app('db')->table('source')->insert([
                'sourcename' => $sourcename,
                'key' => $row['_key'],
                'value' => json_encode($row)
            ]);

            $this->output->progressAdvance();

        });

        $this->output->progressFinish();

    }

}