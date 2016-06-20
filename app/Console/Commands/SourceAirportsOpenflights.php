<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAirportsOpenflights extends Source
{

    protected $signature = 'source:airports_openflights';

    public function handle()
    {
        $sourcename = 'airports_openflights';
        $sourceurl = 'https://raw.githubusercontent.com/jpatokal/openflights/master/data/airports.dat';

        $this->cleanSource($sourcename);

        $keys = [
            'airport_id',
            'name',
            'city',
            'country',
            'code_faa',
            'code_iata',
            'code_icao',
            'latitude',
            'longitude',
            'altitude',
            'timezone',
            'dst',
            'tz'
        ];

        $data = $this->csv(file_get_contents($sourceurl))->fetchAssoc($keys);
        
        foreach ($data as $row) {


            $row['_lat'] = $row['latitude'];
            $row['_lng'] = $row['longitude'];

            app('db')
                ->table('source')
                ->insert([
                    'sourcename' => $sourcename,
                    'value' => json_encode($row)
                ]);

        }


    }

}