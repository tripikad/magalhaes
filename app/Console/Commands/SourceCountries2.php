<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceCountries2 extends Command
{

    protected $signature = 'source:countries2';

    public function handle()
    {
        $sourcename = 'countries2';

        $source = 'http://api.geonames.org/countryInfo?lang=et&username=kristjanjansen';

        $this->line('');
        $this->line('Cleaning previous data');

        app('db')->table('sources')->where('sourcename', '=', $sourcename)->delete();

        $this->line('Downloading source');
        $this->info($source);

        $data = json_decode(
            json_encode(
                simplexml_load_string(
                    file_get_contents($source)
                )
            ), true)
        ['country'];

        $this->line('Inserting data');

        $this->output->progressStart(count((array)$data));

        collect($data)->each(function($row) use ($sourcename) {

            app('db')
                ->table('sources')
                ->insert([
                    'sourcename' => $sourcename,
                    'value' => json_encode($row)
                ]);

            $this->output->progressAdvance();

        });

        $this->output->progressFinish();

    }

}