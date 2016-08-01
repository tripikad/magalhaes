<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use SplFileObject;
use GuzzleHttp;

class SourceHotelWikivoyage extends Source
{

    protected $signature = 'source:hotels_wikivoyage';

    public function handle()
    {

        $sourcename = 'hotels_wikivoyage';

        $this->cleanSource($sourcename);

        $sourceurl = 'https://ckannet-storage.commondatastorage.googleapis.com/2015-09-14T06:28:11.645Z/enwikivoyage-20150901-listings.csv';
  

        $this->line('Downloading file');

        $this->guzzle->get($sourceurl, [
            'verify' => false,
            'save_to' => storage_path('app/source/enwikivoyage-20150901-listings.csv')
        ]);

        $this->line('Adding to database');

        $csv = Reader::createFromPath(new SplFileObject('storage/app/source/enwikivoyage-20150901-listings.csv'));

        $csv->setDelimiter(";");

        $data = $csv->fetchAssoc(0);

        foreach ($data as $row) {

            $row = (object) $row;
            
            app('db')
                ->table('source')
                ->insert([
                    'sourcename' => $sourcename,
                    'value' => json_encode($row)
                ]);

        }

    }

}
