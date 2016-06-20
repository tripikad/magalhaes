<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAirlinesOpenflights extends Source
{

    protected $signature = 'source:airlines_openflights';

    public function handle()
    {
        $sourcename = 'airlines_openflights';
        $sourceurl = 'https://raw.githubusercontent.com/jpatokal/openflights/master/data/airlines.dat';

        $this->cleanSource($sourcename);

        $keys = [
            'airline_id',
            'name',
            'alias',
            'iata_code',
            'icao_code',
            'callsign',
            'country',
            'active',
        ];

        $data = $this->csv(file_get_contents($sourceurl))->fetchAssoc($keys);
        
        foreach ($data as $row) {

            app('db')
                ->table('source')
                ->insert([
                    'sourcename' => $sourcename,
                    'value' => json_encode((object) $row)
                ]);

        }


    }

}