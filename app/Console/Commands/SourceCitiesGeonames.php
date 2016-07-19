<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceCitiesGeonames extends Source
{

    protected $signature = 'source:cities_geonames';

    public function handle()
    {

        // $sourceurl = 'http://download.geonames.org/export/dump/cities15000.zip';
  
        $sourceurl = 'https://github.com/v5analytics/vertexium/blob/master/examples/geonames-cities15000.txt.gz?raw=true';

        $this->line('Downloading file');

        //$this->guzzle->get($sourceurl, [
        //    'save_to' => storage_path('app/source/cities15000.zip')
        //]);

        $this->line('Unzipping');

        $this->ziparchive->open(storage_path('app/source/cities15000.zip'));
        $this->ziparchive->extractTo(storage_path('/app/source/'));
        $this->ziparchive->close();

        $this->line('Done');

    }

}