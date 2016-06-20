<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceRoutesOpenflights extends Source
{

    protected $signature = 'source:routes_openflights';

    public function handle()
    {
        $sourcename = 'routes_openflights';
        $sourceurl = 'https://raw.githubusercontent.com/jpatokal/openflights/master/data/routes.dat';

        $this->cleanSource($sourcename);

        $this->line('Fetching the data');
        $this->info($sourceurl);
        
        $keys = [
            'airline_code',
            'airline_id',
            'airport_source_code',
            'airport_source_id',
            'airport_destination_code',
            'airport_destination_id',
            'codeshare',
            'stops',
            'equipment'
        ];

        $data = $this->csv(file_get_contents($sourceurl))->fetchAssoc($keys);
        
        foreach ($data as $row) {

            app('db')
                ->table('source')
                ->insert([
                    'sourcename' => $sourcename,
                    'value' => json_encode($row)
                ]);

        }


    }

}