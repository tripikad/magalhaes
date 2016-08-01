<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use SplFileObject;

class SourceHotelsWikivoyage extends Source
{

    protected $signature = 'source:hotels_wikivoyage';

    public function handle()
    {

        $sourcename = 'hotels_wikivoyage';

        $this->cleanSource($sourcename);

        $sourceurl = 'https://ckannet-storage.commondatastorage.googleapis.com/2015-09-14T06:28:11.645Z/enwikivoyage-20150901-listings.csv';
  
        $this->line('Downloading file');

        $filepath = storage_path('app/source/enwikivoyage-20150901-listings.csv');
        
        $this->guzzle->get($sourceurl, [
            'verify' => false,
            'save_to' => $filepath
        ]);

        $this->line('Adding to database');

        $csv = Reader::createFromPath(new SplFileObject($filepath));

        $csv->setDelimiter(";");

        $data = $csv->fetchAssoc(0);

        foreach ($data as $row) {

            $row = (object) $row;
            
            if (isset($row->LAT) && isset($row->LON)) {
                $row->_lat = $row->LAT;
                $row->_lng = $row->LON;
            }

            app('db')
                ->table('source')
                ->insert([
                    'sourcename' => $sourcename,
                    'value' => json_encode($row)
                ]);

        }

    }

}
