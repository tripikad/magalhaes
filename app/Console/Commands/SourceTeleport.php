<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ekino\HalClient\HttpClient\FileGetContentsHttpClient;
use Ekino\HalClient\EntryPoint;

class SourceTeleport extends Command
{

    protected $signature = 'source:teleport';

    public function handle()
    {

        $sourcename = 'teleport_regions';

        $source = 'https://api.teleport.org/api/countries/';

        $data = json_decode(file_get_contents($source), true);

        $countries = collect($data['_links']['country:items'])->map(function($country) {
     
            $country['_id'] = collect(explode('/', $country["href"]))->take(-2)->first();
            return $country;
     
        });
        
        foreach ($countries as $country) {

            $row = [
                '_id' => $country['_id'],
                'country' => $country['name'],
            ];

            app('db')
                ->table('source')
                ->insert([
                    'sourcename' => 'teleport_countries',
                    'value' => json_encode($row)
                ]);
    
        }

    }

}