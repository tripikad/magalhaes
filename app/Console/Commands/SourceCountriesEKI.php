<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;


class SourceCountriesEki extends Command
{

    protected $signature = 'source:countries:eki';

    public function handle()
    {

        $sourcename = 'countries_eki';
        $source = 'http://www.eki.ee/knab/mmaad.htm';

        $this->line('');
        $this->line('Cleaning previous data');

        app('db')->table('source')->where('sourcename', '=', $sourcename)->delete();
   
        $this->line('Crawling source');
        $this->info($source);

        $client = new Client();
        $crawler = $client->request('GET', $source);

        $data = collect();

        $crawler->filter('tr > td')->each(function ($node) use ($data) {
            $data->push($node->text());
        });

        $data = $data->chunk(5);

        $this->line('Inserting data');

        $this->output->progressStart(count($data));

        $data->slice(1, $data->count())->each(function($item) use ($sourcename) {
            
            $item = $item->flatten();

            $row = [
                'name' => $item[0],
                'official_name' => $item[1],
                'capital' => $item[2],
                'habitant_name' => $item[3],
                'additional_name' => isset($item[4]) ? $item[4] : '',
            ];

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